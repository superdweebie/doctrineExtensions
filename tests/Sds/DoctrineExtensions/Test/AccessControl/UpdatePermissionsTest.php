<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Sds\Common\AccessControl\Constant\Action;
use Sds\Common\AccessControl\Constant\Role;
use Sds\DoctrineExtensions\AccessControl\UpdatePermissions\Events as Events;
use Sds\DoctrineExtensions\AccessControl\DataModel\Permission;
use Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document\Simple;
use Sds\DoctrineExtensions\Test\BaseTest;

class UpdatePermissionsTest extends BaseTest {

    protected $calls = array();

    public function setUp(){
        parent::setUp();

        $this->configIdentity(true);
        $this->identity->addRole(Role::admin);

        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\AccessControl' => true));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
    }

    public function testUpdatePermissionsDenied(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::preUpdatePermissions, $this);
        $eventManager->addEventListener(Events::onUpdatePermissions, $this);
        $eventManager->addEventListener(Events::postUpdatePermissions, $this);
        $eventManager->addEventListener(Events::updatePermissionsDenied, $this);

        $testDoc = new Simple();

        $testDoc->setPermissions(array(
            new Permission(Role::admin, Action::create),
            new Permission(Role::admin, Action::read),
            new Permission(Role::superAdmin, Action::updatePermissions)
        ));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->addPermission(new Permission(Role::guest, Action::delete));
        $documentManager->flush();

        $this->assertTrue(isset($this->calls[Events::preUpdatePermissions]));
        $this->assertFalse(isset($this->calls[Events::onUpdatePermissions]));
        $this->assertFalse(isset($this->calls[Events::postUpdatePermissions]));
        $this->assertTrue(isset($this->calls[Events::updatePermissionsDenied]));
    }

    public function testUpdatePermissionsGranted(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::preUpdatePermissions, $this);
        $eventManager->addEventListener(Events::onUpdatePermissions, $this);
        $eventManager->addEventListener(Events::postUpdatePermissions, $this);
        $eventManager->addEventListener(Events::updatePermissionsDenied, $this);

        $testDoc = new Simple();

        $testDoc->setPermissions(array(
            new Permission(Role::admin, Action::create),
            new Permission(Role::admin, Action::read),
            new Permission(Role::admin, Action::updatePermissions)
        ));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->addPermission(new Permission(Role::guest, Action::delete));
        $documentManager->flush();

        $this->assertTrue(isset($this->calls[Events::preUpdatePermissions]));
        $this->assertTrue(isset($this->calls[Events::onUpdatePermissions]));
        $this->assertTrue(isset($this->calls[Events::postUpdatePermissions]));
        $this->assertFalse(isset($this->calls[Events::updatePermissionsDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}