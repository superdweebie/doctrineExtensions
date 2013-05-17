<?php

namespace Sds\DoctrineExtensions\Test\SoftDelete;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\SoftDelete\Events;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\SoftDelete\TestAsset\Document\AccessControlled;
use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

class AccessControlAllowTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.softDelete' => true,
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
        $this->softDeleter = $manifest->getServiceManager()->get('softDeleter');
    }

    public function testSoftDeleteAllow(){

        $this->calls = array();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::softDeleteDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->softDeleter->softDelete($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertTrue($this->softDeleter->isSoftDeleted($testDoc));
        $this->assertFalse(isset($this->calls[Events::softDeleteDenied]));
    }

    public function testRestoreGrant(){

        $this->calls = array();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::restoreDenied, $this);

        $testDoc = new AccessControlled();
        $this->softDeleter->softDelete($testDoc);
        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->softDeleter->restore($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertFalse($this->softDeleter->isSoftDeleted($testDoc));
        $this->assertFalse(isset($this->calls[Events::restoreDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}