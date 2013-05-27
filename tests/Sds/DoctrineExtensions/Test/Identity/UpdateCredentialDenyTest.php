<?php

namespace Sds\DoctrineExtensions\Test\Identity;

use Sds\DoctrineExtensions\Identity\Events as Events;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Identity\TestAsset\Document\Identity;
use Sds\DoctrineExtensions\Test\Identity\TestAsset\Document\CredentialTraitDoc;

class UpdateCredentialDenyTest extends BaseTest {

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

    public function testCredentialUpdateDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::preUpdateCredential, $this);
        $eventManager->addEventListener(Events::onUpdateCredential, $this);
        $eventManager->addEventListener(Events::postUpdateCredential, $this);
        $eventManager->addEventListener(Events::updateCredentialDenied, $this);

        $testDoc = new CredentialTraitDoc();
        $testDoc->setCredential('password1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->setCredential('password2');
        $documentManager->flush();

        $this->assertTrue(isset($this->calls[Events::preUpdateCredential]));
        $this->assertFalse(isset($this->calls[Events::onUpdateCredential]));
        $this->assertFalse(isset($this->calls[Events::postUpdateCredential]));
        $this->assertTrue(isset($this->calls[Events::updateCredentialDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}