<?php

namespace Sds\DoctrineExtensions\Test\Freeze;

use Sds\DoctrineExtensions\Freeze\Events;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document\AccessControlled;

class AccessControlFreezeDenyTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $manifest = $this->getManifest(['extensionConfigs' => [
            'Sds\DoctrineExtensions\Freeze' => true,
            'Sds\DoctrineExtensions\AccessControl' => true
        ]]);

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

    public function testFreezeDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::freezeDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->freezer->freeze($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertFalse($this->freezer->isFrozen($testDoc));
        $this->assertTrue(isset($this->calls[Events::freezeDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}