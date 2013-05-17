<?php

namespace Sds\DoctrineExtensions\Test\Freeze;

use Sds\DoctrineExtensions\Freeze\Events;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document\AccessControlled;
use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

class AccessControlAllowTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.freeze' => true,
                'extension.accessControl' => true
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                    'identity' => function(){
                        $identity = new RoleAwareIdentity();
                        $identity->setIdentityName('toby')->addRole('admin');
                        return $identity;
                    }
                ]
            ]
        ]);

        $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
        $this->freezer = $manifest->getServiceManager()->get('freezer');
    }

    public function testFreezeAllow(){

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
        $this->assertTrue($this->freezer->isFrozen($testDoc));
        $this->assertFalse(isset($this->calls[Events::freezeDenied]));
    }

    public function testThawGrant(){

        $this->calls = array();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::thawDenied, $this);

        $testDoc = new AccessControlled();
        $this->freezer->freeze($testDoc);
        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->freezer->thaw($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertFalse($this->freezer->isFrozen($testDoc));
        $this->assertFalse(isset($this->calls[Events::thawDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}