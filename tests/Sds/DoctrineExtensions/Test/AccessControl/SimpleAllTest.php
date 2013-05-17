<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Sds\DoctrineExtensions\AccessControl\Events as AccessControlEvents;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document\Simple;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\Identity;

class SimpleAllTest extends BaseTest {

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
                        $identity = new Identity();
                        $identity->setIdentityName('toby');
                        return $identity;
                    }
                ]
            ]
       ]);

       $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
    }

    public function testCreateDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);

        $testDoc = new Simple();

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $this->assertTrue(isset($this->calls[AccessControlEvents::createDenied]));

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc);
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}