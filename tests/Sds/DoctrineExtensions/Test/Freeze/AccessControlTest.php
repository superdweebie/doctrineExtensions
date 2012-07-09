<?php

namespace Sds\DoctrineExtensions\Test\Freeze;

use Sds\DoctrineExtensions\AccessControl\Constant\Role;
use Sds\DoctrineExtensions\AccessControl\Model\Permission;
use Sds\DoctrineExtensions\Freeze\AccessControl\Constant\Action;
use Sds\DoctrineExtensions\Freeze\AccessControl\Event\Events;
use Sds\DoctrineExtensions\Freeze\ExtensionConfig;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document\AccessControlled;

class AccessControlTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configActiveUser(true);

        $extensionConfig = new ExtensionConfig();
        $extensionConfig->setAccessControlFreeze(true);
        $extensionConfig->setAccessControlThaw(true);
        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\Freeze' => $extensionConfig));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testFreezeDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::freezeDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->freeze();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertFalse($testDoc->getFrozen());
        $this->assertTrue(isset($this->calls[Events::freezeDenied]));
    }

    public function testFreezeGrant(){

        $this->calls = array();
        $this->activeUser->addRole(Role::guest);

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::freezeDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('version 1');
        $testDoc->addPermission(new Permission(Role::guest, Action::freeze));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->freeze();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertTrue($testDoc->getFrozen());
        $this->assertFalse(isset($this->calls[Events::freezeDenied]));
    }

    public function testThawDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::thawDenied, $this);

        $testDoc = new AccessControlled();
        $testDoc->freeze();
        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->thaw();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertTrue($testDoc->getFrozen());
        $this->assertTrue(isset($this->calls[Events::thawDenied]));
    }

    public function testThawGrant(){

        $this->calls = array();
        $this->activeUser->addRole(Role::guest);

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::thawDenied, $this);

        $testDoc = new AccessControlled();
        $testDoc->freeze();
        $testDoc->setName('version 1');
        $testDoc->addPermission(new Permission(Role::guest, Action::thaw));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->thaw();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertFalse($testDoc->getFrozen());
        $this->assertFalse(isset($this->calls[Events::thawDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}