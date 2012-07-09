<?php

namespace Sds\DoctrineExtensions\Test\Zone;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Zone\TestAsset\Document\Simple;

class ZoneTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\Zone' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Zone\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testBasicFunction(){

        $documentManager = $this->documentManager;
        $testDoc = new Simple();

        $testDoc->setName('miriam');
        $testDoc->setZones(array('zone1', 'zone2'));
        $testDoc->addZone('zone3');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $zones = $testDoc->getZones();
        sort($zones);
        $this->assertEquals(array('zone1', 'zone2', 'zone3'), $zones);

        $testDoc->removeZone('zone3');

        $zones = $testDoc->getZones();
        sort($zones);
        $this->assertEquals(array('zone1', 'zone2'), $zones);
    }

    public function testFilter() {

        $documentManager = $this->documentManager;
        $documentManager->getFilterCollection()->enable('zone');

        $testDocA = new Simple();
        $testDocA->setName('miriam');
        $testDocA->setZones(array('zone1', 'zone2'));

        $testDocB = new Simple();
        $testDocB->setName('lucy');
        $testDocB->setZones(array('zone2', 'zone3'));

        $documentManager->persist($testDocA);
        $documentManager->persist($testDocB);
        $documentManager->flush();
        $ids = array($testDocA->getId(), $testDocB->getId());
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('lucy', 'miriam'), $docNames);

        $documentManager->getFilterCollection()->enable('zone');
        $filter = $documentManager->getFilterCollection()->getFilter('zone');

        $filter->setZones(array('zone1'));

        $documentManager->flush();
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('miriam'), $docNames);

        $filter->setZones(array('zone1', 'zone3'));
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('lucy', 'miriam'), $docNames);

        $filter->setZones(array('zone2'));
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('lucy', 'miriam'), $docNames);

        $filter->excludeZoneList();
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertCount(0, $docNames);

        $filter->setZones(array('zone1'));
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('lucy'), $docNames);

        $documentManager->getFilterCollection()->disable('zone');
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('lucy', 'miriam'), $docNames);
    }

    protected function getTestDocs(){
        $repository = $this->documentManager->getRepository('Sds\DoctrineExtensions\Test\Zone\TestAsset\Document\Simple');
        $testDocs = $repository->findAll();
        $returnDocs = array();
        $returnNames = array();
        foreach ($testDocs as $testDoc){
            $returnDocs[] = $testDoc;
            $returnNames[] = $testDoc->getName();
        }
        sort($returnNames);
        return array($returnDocs, $returnNames);
    }
}