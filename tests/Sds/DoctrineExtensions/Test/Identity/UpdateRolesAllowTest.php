<?php

namespace Sds\DoctrineExtensions\Test\Identity;

use Sds\DoctrineExtensions\Identity\Events as Events;
use Sds\DoctrineExtensions\Test\Identity\TestAsset\Document\Identity;
use Sds\DoctrineExtensions\Test\BaseTest;

class UpdateRolesAllowTest extends BaseTest {

    protected $calls = array();

    public function setUp(){
        parent::setUp();

        $this->configIdentity(true);
        $this->identity->addRole('admin');

        $manifest = $this->getManifest(['extensionConfigs' => [
            'Sds\DoctrineExtensions\AccessControl' => true,
            'Sds\DoctrineExtensions\Identity' => true
        ]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Identity\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
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

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

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