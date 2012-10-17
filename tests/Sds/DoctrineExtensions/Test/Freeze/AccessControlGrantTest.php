<?php

namespace Sds\DoctrineExtensions\Test\Freeze;

use Sds\Common\AccessControl\Constant\Role;
use Sds\DoctrineExtensions\AccessControl\DataModel\Permission;
use Sds\DoctrineExtensions\Freeze\AccessControl\Constant\Action;
use Sds\DoctrineExtensions\Freeze\AccessControl\Events;
use Sds\DoctrineExtensions\Freeze\ExtensionConfig;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document\AccessControlled;

class AccessControlGrantTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configIdentity(true);
        $this->identity->addRole(Role::guest);

        $extensionConfig = new ExtensionConfig();
        $extensionConfig->setEnableAccessControl(true);
        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\Freeze' => $extensionConfig));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
    }

    public function testFreezeGrant(){

        $this->calls = array();

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

    public function testThawGrant(){

        $this->calls = array();

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