<?php

namespace SdsDoctrineExtensionsTest\Readonly;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\Stamp\TestAsset\Document\Simple;
use SdsDoctrineExtensionsTest\Stamp\TestAsset\User;
use SdsDoctrineExtensions\Stamp\ExtensionConfig as StampConfig;

class StampTest extends BaseTest {

    protected $user;
    protected $subscriber;

    public function setUp(){

        parent::setUp();

        $user = new User();
        $user->setUsername('toby');
        $this->user = $user;

        $manifest = $this->getManifest(array('SdsDoctrineExtensions\Stamp' => new StampConfig($user)));

        $this->configure(
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

        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('version1', $testDoc->getName());
        $this->assertEquals('toby', $testDoc->getCreatedBy());
        $this->assertNotNull($testDoc->getCreatedOn());
        $this->assertNull($testDoc->getUpdatedBy());
        $this->assertNull($testDoc->getUpdatedOn());

        $this->user->setUsername('lucy');
        $this->subscriber->setActiveUser($this->user);

        $testDoc->setName('version2');

        $id = $this->persist($testDoc);

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('version2', $testDoc->getName());
        $this->assertEquals('toby', $testDoc->getCreatedBy());
        $this->assertNotNull($testDoc->getCreatedOn());
        $this->assertEquals('lucy', $testDoc->getUpdatedBy());
        $this->assertNotNull($testDoc->getUpdatedOn());
    }
}