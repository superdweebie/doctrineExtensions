<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Sds\DoctrineExtensions\AccessControl\Events as AccessControlEvents;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document\NoPermissions;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

class NoPermissionsTest extends BaseTest {

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

    public function testNoPermissions(){
        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);

        $testDoc = new NoPermissions();
        $testDoc->setName('nathan');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $documentManager->flush();

        $this->assertTrue(isset($this->calls[AccessControlEvents::createDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}