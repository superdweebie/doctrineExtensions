<?php

namespace Sds\DoctrineExtensions\Test\Freeze;

use Sds\DoctrineExtensions\Freeze\Events;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document\AccessControlled;

class AccessControlFreezeDenyTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.freeze' => true,
                'extension.accesscontrol' => true
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                ]
            ]
        ]);

        $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
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