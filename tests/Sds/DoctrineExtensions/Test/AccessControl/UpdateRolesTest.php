<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Sds\Common\AccessControl\Constant\Action;
use Sds\Common\AccessControl\Constant\Role;
use Sds\DoctrineExtensions\AccessControl\UpdateRoles\Events as Events;
use Sds\DoctrineExtensions\AccessControl\DataModel\Permission;
use Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document\Identity;
use Sds\DoctrineExtensions\Test\BaseTest;

class UpdateRolesTest extends BaseTest {

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

    public function testUpdateRolesDenied(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::preUpdateRoles, $this);
        $eventManager->addEventListener(Events::onUpdateRoles, $this);
        $eventManager->addEventListener(Events::postUpdateRoles, $this);
        $eventManager->addEventListener(Events::updateRolesDenied, $this);

        $testDoc = new Identity();

        $testDoc->setPermissions(array(
            new Permission(Role::admin, Action::create),
            new Permission(Role::admin, Action::read),
            new Permission(Role::superAdmin, Action::updateRoles)
        ));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->addRole(Role::user);
        $documentManager->flush();

        $this->assertTrue(isset($this->calls[Events::preUpdateRoles]));
        $this->assertFalse(isset($this->calls[Events::onUpdateRoles]));
        $this->assertFalse(isset($this->calls[Events::postUpdateRoles]));
        $this->assertTrue(isset($this->calls[Events::updateRolesDenied]));
    }

    public function testPermissionsUpdateGranted(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::preUpdateRoles, $this);
        $eventManager->addEventListener(Events::onUpdateRoles, $this);
        $eventManager->addEventListener(Events::postUpdateRoles, $this);
        $eventManager->addEventListener(Events::updateRolesDenied, $this);

        $testDoc = new Identity();

        $testDoc->setPermissions(array(
            new Permission(Role::admin, Action::create),
            new Permission(Role::admin, Action::read),
            new Permission(Role::admin, Action::updateRoles)
        ));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->addRole(Role::user);
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