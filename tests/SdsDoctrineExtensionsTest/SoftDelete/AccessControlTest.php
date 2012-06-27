<?php

namespace SdsDoctrineExtensionsTest\SoftDelete;

use SdsDoctrineExtensions\AccessControl\Constant\Role;
use SdsDoctrineExtensions\AccessControl\Model\Permission;
use SdsDoctrineExtensions\SoftDelete\AccessControl\Constant\Action;
use SdsDoctrineExtensions\SoftDelete\AccessControl\Event\Events;
use SdsDoctrineExtensions\SoftDelete\ExtensionConfig;
use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Document\AccessControlled;

class AccessControlTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configActiveUser(true);

        $extensionConfig = new ExtensionConfig();
        $extensionConfig->setAccessControlSoftDelete(true);
        $extensionConfig->setAccessControlRestore(true);
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\SoftDelete' => $extensionConfig));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testSoftDeleteDeny(){

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

        $testDoc->softDelete();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertFalse($testDoc->getSoftDeleted());
        $this->assertTrue(isset($this->calls[Events::softDeleteDenied]));
    }

    public function testSoftDeleteGrant(){

        $this->calls = array();
        $this->activeUser->addRole(Role::guest);

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::softDeleteDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('version 1');
        $testDoc->addPermission(new Permission(Role::guest, Action::softDelete));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->softDelete();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertTrue($testDoc->getSoftDeleted());
        $this->assertFalse(isset($this->calls[Events::softDeleteDenied]));
    }

    public function testRestoreDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::restoreDenied, $this);

        $testDoc = new AccessControlled();
        $testDoc->softDelete();
        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->restore();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertTrue($testDoc->getSoftDeleted());
        $this->assertTrue(isset($this->calls[Events::restoreDenied]));
    }

    public function testRestoreGrant(){

        $this->calls = array();
        $this->activeUser->addRole(Role::guest);

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::restoreDenied, $this);

        $testDoc = new AccessControlled();
        $testDoc->softDelete();
        $testDoc->setName('version 1');
        $testDoc->addPermission(new Permission(Role::guest, Action::restore));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->restore();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertFalse($testDoc->getSoftDeleted());
        $this->assertFalse(isset($this->calls[Events::restoreDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}