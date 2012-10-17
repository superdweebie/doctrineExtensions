<?php

namespace Sds\DoctrineExtensions\Test\State;

use Sds\DoctrineExtensions\State\AccessControl\Events;
use Sds\DoctrineExtensions\State\ExtensionConfig;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\State\TestAsset\Document\AccessControlled;

class AccessControlDenyTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configIdentity(true);

        $extensionConfig = new ExtensionConfig();
        $extensionConfig->setEnableAccessControl(true);
        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\State' => $extensionConfig));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\State\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
    }

    public function testTransitionDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::transitionDenied, $this);

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
        $this->assertTrue(isset($this->calls[Events::transitionDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}