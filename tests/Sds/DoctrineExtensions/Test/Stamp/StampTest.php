<?php

namespace Sds\DoctrineExtensions\Test\Stamp;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Stamp\TestAsset\Document\Simple;

class StampTest extends BaseTest {

    protected $subscriber;

    public function setUp(){

        parent::setUp();

        $this->configIdentity();

        $manifest = $this->getManifest(['extensionConfigs' => ['Sds\DoctrineExtensions\Stamp' => true]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Stamp\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();

        $this->subscriber = $manifest->getSubscribers()[0];
    }

    public function testStamp(){

        $documentManager = $this->documentManager;
        $testDoc = new Simple();
        $testDoc->setName('version1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('version1', $testDoc->getName());
        $this->assertEquals('toby', $testDoc->getCreatedBy());
        $this->assertNotNull($testDoc->getCreatedOn());
        $this->assertEquals('toby', $testDoc->getUpdatedBy());
        $this->assertNotNull($testDoc->getUpdatedOn());

        $testDoc->setName('version2');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('version2', $testDoc->getName());
        $this->assertEquals('toby', $testDoc->getCreatedBy());
        $this->assertNotNull($testDoc->getCreatedOn());
        $this->assertEquals('toby', $testDoc->getUpdatedBy());
        $this->assertNotNull($testDoc->getUpdatedOn());
    }
}