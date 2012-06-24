<?php

namespace SdsDoctrineExtensionsTest\SoftDelete;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Document\Simple;
use SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Subscriber;

class SoftDeleteTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\SoftDelete' => null));

        $this->configure(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testBasicFunction(){

        $documentManager = $this->documentManager;
        $testDoc = new Simple();

        $testDoc->setName('version 1');

        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertFalse($testDoc->getSoftDeleted());

        $testDoc->softDelete();

        $documentManager->flush();
        $documentManager->clear();
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertTrue($testDoc->getSoftDeleted());

        $testDoc->setName('version 2');
        
        $documentManager->flush();
        $documentManager->clear();
        $testDoc = null;
        $testDoc = $repository->find($id);
        
        $this->assertEquals('version 1', $testDoc->getName());
        
        $testDoc->restore();

        $documentManager->flush();
        $documentManager->clear();
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertFalse($testDoc->getSoftDeleted());
    }

    public function testFilter() {

        $documentManager = $this->documentManager;
        $documentManager->getFilterCollection()->enable('softDelete');

        $testDocA = new Simple();
        $testDocA->setName('miriam');

        $testDocB = new Simple();
        $testDocB->setName('lucy');

        $ids = array(
            $this->persist($testDocA),
            $this->persist($testDocB)
        );

        $testDocA = null;
        $testDocB = null;

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('lucy', 'miriam'), $docNames);

        if ($testDocs[0]->getName() == 'lucy'){
            $testDocs[0]->softDelete();
        } else {
            $testDocs[1]->softDelete();
        }

        $documentManager->flush();
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('miriam'), $docNames);

        $documentManager->getFilterCollection()->disable('softDelete');

        $documentManager->flush();
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('lucy', 'miriam'), $docNames);

        if ($testDocs[0]->getName() == 'lucy'){
            $testDocs[0]->restore();
        } else {
            $testDocs[1]->restore();
        }

        $documentManager->getFilterCollection()->enable('softDelete');

        $documentManager->flush();
        $documentManager->clear();

        list($testDocs, $docNames) = $this->getTestDocs();
        $this->assertEquals(array('lucy', 'miriam'), $docNames);
    }

    protected function getTestDocs(){
        $repository = $this->documentManager->getRepository('SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Document\Simple');
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

    public function testEvents() {

        $subscriber = new Subscriber();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();
        $eventManager->addEventSubscriber($subscriber);

        $testDoc = new Simple();

        $testDoc->setName('version 1');

        $id = $this->persist($testDoc);

        $this->assertFalse($subscriber->getPreDeleteCalled());
        $this->assertFalse($subscriber->getPostDeleteCalled());
        $this->assertFalse($subscriber->getPreSoftRestoreCalled());
        $this->assertFalse($subscriber->getPostSoftRestoreCalled());

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertFalse($testDoc->getSoftDeleted());

        $testDoc->softDelete();
        $subscriber->reset();

        $documentManager->flush();

        $this->assertTrue($subscriber->getPreDeleteCalled());
        $this->assertTrue($subscriber->getPostDeleteCalled());
        $this->assertFalse($subscriber->getPreSoftRestoreCalled());
        $this->assertFalse($subscriber->getPostSoftRestoreCalled());

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertTrue($testDoc->getSoftDeleted());

        $testDoc->setName('version 2');
        $subscriber->reset();
        $documentManager->flush();
        
        $this->assertTrue($subscriber->getSoftDeleteUpdateDeniedCalled());        
        
        $testDoc->restore();
        $subscriber->reset();

        $documentManager->flush();

        $this->assertFalse($subscriber->getPreDeleteCalled());
        $this->assertFalse($subscriber->getPostDeleteCalled());
        $this->assertTrue($subscriber->getPreSoftRestoreCalled());
        $this->assertTrue($subscriber->getPostSoftRestoreCalled());

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertFalse($testDoc->getSoftDeleted());

        $testDoc->softDelete();
        $subscriber->reset();
        $subscriber->setRollbackDelete(true);

        $documentManager->flush();

        $this->assertTrue($subscriber->getPreDeleteCalled());
        $this->assertFalse($subscriber->getPostDeleteCalled());
        $this->assertFalse($subscriber->getPreSoftRestoreCalled());
        $this->assertFalse($subscriber->getPostSoftRestoreCalled());

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertFalse($testDoc->getSoftDeleted());
        $testDoc->softDelete();
        $subscriber->reset();
        $documentManager->flush();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertTrue($testDoc->getSoftDeleted());

        $testDoc->restore();
        $subscriber->reset();
        $subscriber->setRollbackRestore(true);

        $documentManager->flush();

        $this->assertFalse($subscriber->getPreDeleteCalled());
        $this->assertFalse($subscriber->getPostDeleteCalled());
        $this->assertTrue($subscriber->getPreSoftRestoreCalled());
        $this->assertFalse($subscriber->getPostSoftRestoreCalled());
    }
}