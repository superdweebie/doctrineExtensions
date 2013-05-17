<?php

namespace Sds\DoctrineExtensions\Test\Identity;

use Sds\DoctrineExtensions\Identity\Events as Events;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Identity\TestAsset\Document\Identity;

class UpdateRolesAllowTest extends BaseTest {

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
                        $identity->setIdentityName('toby')->addRole('admin');
                        return $identity;
                    }
                ]
            ]
        ]);

        $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
    }

    public function testRolesUpdateAllow(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::preUpdateRoles, $this);
        $eventManager->addEventListener(Events::onUpdateRoles, $this);
        $eventManager->addEventListener(Events::postUpdateRoles, $this);
        $eventManager->addEventListener(Events::updateRolesDenied, $this);

        $testDoc = new Identity();
        $testDoc->setIdentityName('test-name');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find('test-name');

        $testDoc->addRole('editor');
        $documentManager->flush();

        $this->assertTrue(isset($this->calls[Events::preUpdateRoles]));
        $this->assertTrue(isset($this->calls[Events::onUpdateRoles]));
        $this->assertTrue(isset($this->calls[Events::postUpdateRoles]));
        $this->assertFalse(isset($this->calls[Events::updateRolesDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}