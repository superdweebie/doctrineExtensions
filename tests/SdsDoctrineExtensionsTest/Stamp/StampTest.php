<?php

namespace SdsDoctrineExtensionsTest\Readonly;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\Stamp\TestAsset\Document\Simple;

class StampTest extends BaseTest {

    protected $subscriber;

    public function setUp(){

        parent::setUp();

        $this->configActiveUser();

        $manifest = $this->getManifest(array('SdsDoctrineExtensions\Stamp' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\Stamp\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );

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
        $this->assertNull($testDoc->getUpdatedBy());
        $this->assertNull($testDoc->getUpdatedOn());

        $this->activeUser->setUsername('lucy');
        $this->subscriber->setActiveUser($this->activeUser);

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
        $this->assertEquals('lucy', $testDoc->getUpdatedBy());
        $this->assertNotNull($testDoc->getUpdatedOn());
    }
}