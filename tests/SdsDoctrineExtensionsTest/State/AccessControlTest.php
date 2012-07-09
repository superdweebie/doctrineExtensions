<?php

namespace SdsDoctrineExtensionsTest\State;

use SdsDoctrineExtensions\AccessControl\Constant\Role;
use SdsDoctrineExtensions\AccessControl\Model\Permission;
use SdsDoctrineExtensions\State\AccessControl\Event\Events;
use SdsDoctrineExtensions\State\ExtensionConfig;
use SdsDoctrineExtensions\State\Transition;
use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\State\TestAsset\Document\AccessControlled;

class AccessControlTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configActiveUser(true);

        $extensionConfig = new ExtensionConfig();
        $extensionConfig->setAccessControlStateChange(true);
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\State' => $extensionConfig));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\State\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testStateChangeDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::stateChangeDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('version 1');
        $testDoc->setState('state1');
        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->setState('state2');

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertEquals('state1', $testDoc->getState());
        $this->assertTrue(isset($this->calls[Events::stateChangeDenied]));
    }

    public function testStateChangeGrant(){

        $this->calls = array();
        $this->activeUser->addRole(Role::guest);

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::stateChangeDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('version 1');
        $testDoc->setState('state1');
        $testDoc->addPermission(new Permission(Role::guest, Transition::getAction('state1', 'state2'), 'state1'));

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->setState('state2');

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertEquals('state2', $testDoc->getState());
        $this->assertFalse(isset($this->calls[Events::stateChangeDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}