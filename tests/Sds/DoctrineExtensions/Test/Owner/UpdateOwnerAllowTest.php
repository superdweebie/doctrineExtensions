<?php

namespace Sds\DoctrineExtensions\Test\Owner;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\AccessControl\Events as Events;
use Sds\DoctrineExtensions\Test\Owner\TestAsset\Document\OwnerDoc;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

class UpdateOwnerAllowTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.accessControl' => true,
                'extension.owner' => true
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
    }

    public function testOwnerUpdateAllow(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::updateDenied, $this);

        $testDoc = new OwnerDoc();

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->setOwner('bobby');
        $documentManager->flush();

        $this->assertFalse(isset($this->calls[Events::updateDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}