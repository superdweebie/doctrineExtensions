<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Sds\DoctrineExtensions\AccessControl\Events as AccessControlEvents;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document\Simple;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

class SimpleAdminTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
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
    }

    public function testUpdateAllow(){
        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::updateDenied, $this);

        $testDoc = new Simple();
        $testDoc->setName('nathan');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));

        $testDoc = $repository->find($id);
        $testDoc->setName('changed');

        $documentManager->flush();

        $this->assertFalse(isset($this->calls[AccessControlEvents::updateDenied]));

        $documentManager->clear();
        $testDoc = $repository->find($id);

        $this->assertEquals('changed', $testDoc->getName());
    }

    public function testDeleteDeny(){
        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::deleteDenied, $this);

        $testDoc = new Simple();
        $testDoc->setName('lucy');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));

        $testDoc = $repository->find($id);
        $documentManager->remove($testDoc);

        $documentManager->flush();

        $this->assertTrue(isset($this->calls[AccessControlEvents::deleteDenied]));

        $documentManager->clear();
        $testDoc = $repository->find($id);

        $this->assertEquals('lucy', $testDoc->getName());
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}