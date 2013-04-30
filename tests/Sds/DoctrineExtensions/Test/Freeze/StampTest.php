<?php

namespace Sds\DoctrineExtensions\Test\Freeze;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document\Stamped;

class StampTest extends BaseTest {

    public function setUp(){

        parent::setUp();

        $this->configIdentity();

        $manifest = $this->getManifest(['extensionConfigs' => ['Sds\DoctrineExtensions\Freeze' => true]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
        $this->freezer = $manifest->getServiceManager()->get('freezer');
    }

    public function testStamps() {

        $documentManager = $this->documentManager;
        $testDoc = new Stamped();
        $testDoc->setName('version1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc->getFrozenBy());
        $this->assertNull($testDoc->getFrozenOn());
        $this->assertNull($testDoc->getThawedBy());
        $this->assertNull($testDoc->getThawedOn());

        $this->freezer->freeze($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('toby', $testDoc->getFrozenBy());
        $this->assertNotNull($testDoc->getFrozenOn());
        $this->assertNull($testDoc->getThawedBy());
        $this->assertNull($testDoc->getThawedOn());

        $this->freezer->thaw($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('toby', $testDoc->getFrozenBy());
        $this->assertNotNull($testDoc->getFrozenOn());
        $this->assertEquals('toby', $testDoc->getThawedBy());
        $this->assertNotNull($testDoc->getThawedOn());
    }
}