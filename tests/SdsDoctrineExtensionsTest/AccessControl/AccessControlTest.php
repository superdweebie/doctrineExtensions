<?php

namespace SdsDoctrineExtensionsTest\AccessControl;

use SdsDoctrineExtensions\AccessControl\AccessController;
use SdsDoctrineExtensions\AccessControl\Event\Events as AccessControlEvents;
use SdsDoctrineExtensions\AccessControl\Constant\Action;
use SdsDoctrineExtensions\AccessControl\Constant\Role;
use SdsDoctrineExtensions\AccessControl\Model\Permission;
use SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document\Simple;
use SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document\StateAware;
use SdsDoctrineExtensionsTest\BaseTest;

class AccessControlTest extends BaseTest {

    protected $calls = array();

    public function setUp(){
        parent::setUp();

        $this->configActiveUser(true);

        $manifest = $this->getManifest(array('SdsDoctrineExtensions\AccessControl' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testGetAllowedActions(){

        $documentManager = $this->documentManager;

        $this->activeUser->addRole(Role::guest);

        $testDoc = new Simple();

        $testDoc->setPermissions(array(
            new Permission(Role::user, Action::create),
            new Permission(Role::guest, Action::read),
            new Permission(Role::guest, Action::update)
        ));

        $this->assertEquals(array('read', 'update'), AccessController::getAllowedActions($testDoc, $this->activeUser));
    }

    public function testCreateControlNoStateDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);

        $this->activeUser->addRole(Role::guest);

        $testDoc = new Simple();

        $testDoc->setPermissions(array(
            new Permission(Role::user, Action::create)
        ));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $this->assertTrue(isset($this->calls[AccessControlEvents::createDenied]));

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc);
    }

    public function testCreateControlNoStateGrant(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);

        $this->activeUser->addRole(Role::user);

        $testDoc = new Simple();

        $testDoc->setPermissions(array(
            new Permission(Role::user, Action::create)
        ));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $this->assertFalse(isset($this->calls[AccessControlEvents::createDenied]));

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->assertEquals($id, $testDoc->getId());
    }

    public function testCreateControlWithStateDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);

        $this->activeUser->addRole(Role::user);

        $testDoc = new StateAware();

        $testDoc->setPermissions(array(
            new Permission(Role::user, Action::create, 'new')
        ));
        $testDoc->setState('notWork');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $this->assertTrue(isset($this->calls[AccessControlEvents::createDenied]));

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc);
    }

    public function testCreateControlWithStateGrant(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);

        $this->activeUser->addRole(Role::user);

        $testDoc = new StateAware();

        $testDoc->setPermissions(array(
            new Permission(Role::user, Action::create, 'new')
        ));
        $testDoc->setState('new');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $this->assertFalse(isset($this->calls[AccessControlEvents::createDenied]));

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->assertEquals($id, $testDoc->getId());
    }

    public function testReadControlNoState(){

        $documentManager = $this->documentManager;

        $this->activeUser->addRole(Role::guest);
        $filter = $documentManager->getFilterCollection()->enable('readAccessControl');
        $filter->setActiveUser($this->activeUser);

        $toby = new Simple();
        $toby->setName('toby');
        $toby->setPermissions(array(
            new Permission(Role::guest, Action::create),
            new Permission(Role::user, Action::read)
        ));

        $miriam = new Simple();
        $miriam->setName('miriam');
        $miriam->setPermissions(array(
            new Permission(Role::guest, Action::create),
            new Permission(Role::guest, Action::read)
        ));

        $documentManager->persist($toby);
        $documentManager->persist($miriam);
        $documentManager->flush();
        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($toby));

        $this->assertEquals(array('miriam'), $this->getAllNames($repository));

        $this->activeUser->addRole(Role::user);

        $documentManager->clear();

        $this->assertEquals(array('miriam', 'toby'), $this->getAllNames($repository));
    }

    public function testReadControlWithState(){

        $documentManager = $this->documentManager;

        $this->activeUser->addRole(Role::guest);
        $filter = $documentManager->getFilterCollection()->enable('readAccessControl');
        $filter->setActiveUser($this->activeUser);

        $toby = new StateAware();
        $toby->setName('toby');
        $toby->setState('draft');
        $toby->setPermissions(array(
            new Permission(Role::guest, Action::create, 'draft'),
            new Permission(Role::guest, Action::read, 'draft')
        ));

        $miriam = new StateAware();
        $miriam->setName('miriam');
        $miriam->setState('draft2');
        $miriam->setPermissions(array(
            new Permission(Role::guest, Action::create, 'draft2'),
            new Permission(Role::guest, Action::read, 'draft')
        ));

        $documentManager->persist($toby);
        $documentManager->persist($miriam);
        $documentManager->flush();

        $tobyId = $toby->getId();
        $miriamId = $miriam->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($toby));

        $this->assertEquals(array('toby'), $this->getAllNames($repository));

        $documentManager->getFilterCollection()->disable('readAccessControl');

        $miriam = $repository->find($miriamId);
        $miriam->setState('draft');

        $documentManager->flush();
        $documentManager->clear();

        $filter = $documentManager->getFilterCollection()->enable('readAccessControl');
        $filter->setActiveUser($this->activeUser);

        $this->assertEquals(array('miriam', 'toby'), $this->getAllNames($repository));
    }

    public function testUpdateControlDeny(){
        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::updateDenied, $this);

        $this->activeUser->addRole(Role::guest);

        $testDoc = new Simple();
        $testDoc->setName('lucy');
        $testDoc->setPermissions(array(
            new Permission(Role::guest, Action::create),
            new Permission(Role::user, Action::update)
        ));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));

        $testDoc = $repository->find($id);
        $testDoc->setName('changed');

        $documentManager->flush();

        $this->assertTrue(isset($this->calls[AccessControlEvents::updateDenied]));

        $documentManager->clear();
        $testDoc = $repository->find($id);

        $this->assertEquals('lucy', $testDoc->getName());
    }

    public function testUpdateControlGrant(){
        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::updateDenied, $this);

        $this->activeUser->addRole(Role::guest);

        $testDoc = new Simple();
        $testDoc->setName('lucy');
        $testDoc->setPermissions(array(
            new Permission(Role::guest, Action::create),
            new Permission(Role::guest, Action::update)
        ));

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

    public function testDeleteControlDeny(){
        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::deleteDenied, $this);

        $this->activeUser->addRole(Role::guest);

        $testDoc = new Simple();
        $testDoc->setName('lucy');
        $testDoc->setPermissions(array(
            new Permission(Role::guest, Action::create),
            new Permission(Role::user, Action::delete)
        ));

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

    public function testDeleteControlGrant(){
        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::deleteDenied, $this);

        $this->activeUser->addRole(Role::guest);

        $testDoc = new Simple();
        $testDoc->setName('lucy');
        $testDoc->setPermissions(array(
            new Permission(Role::guest, Action::create),
            new Permission(Role::guest, Action::delete)
        ));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));

        $testDoc = $repository->find($id);
        $documentManager->remove($testDoc);

        $documentManager->flush();

        $this->assertFalse(isset($this->calls[AccessControlEvents::deleteDenied]));

        $documentManager->clear();
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc);
    }

    protected function getAllNames($repository){
        $names = array();
        $documents = $repository->findAll();
        foreach ($documents as $document){
            $names[] = $document->getName();
        }
        sort($names);
        return $names;
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}