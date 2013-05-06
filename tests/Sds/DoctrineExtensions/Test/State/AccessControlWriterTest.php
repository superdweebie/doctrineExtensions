<?php

namespace Sds\DoctrineExtensions\Test\State;

use Sds\DoctrineExtensions\State\Events;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\State\TestAsset\Document\AccessControlled;

class AccessControlWriterTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configIdentity(true);
        $this->identity->addRole('writer');

        $manifest = $this->getManifest(['extensionConfigs' => [
            'Sds\DoctrineExtensions\State' => true,
            'Sds\DoctrineExtensions\AccessControl' => true
        ]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\State\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
    }

    public function testCreateDeny(){

        $this->calls = array();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener('createDenied', $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('deny');
        $testDoc->setState('published');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $this->assertNull($testDoc->getId());
        $this->assertTrue(isset($this->calls['createDenied']));
    }

    public function testTransitionAllow(){

        $this->calls = array();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::transitionDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('version 1');
        $testDoc->setState('draft');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $testDoc->setState('review');

        $documentManager->flush();

        $this->assertEquals('review', $testDoc->getState());
        $this->assertFalse(isset($this->calls[Events::transitionDenied]));
    }

    public function testTransitionDeny(){

        $this->calls = array();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::transitionDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('nice doc');
        $testDoc->setState('draft');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $testDoc->setState('published');

        $documentManager->flush();

        $this->assertEquals('draft', $testDoc->getState());
        $this->assertTrue(isset($this->calls[Events::transitionDenied]));
    }

    public function testTransitionDeny2(){

        $this->calls = array();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::transitionDenied, $this);

        $testDoc = new AccessControlled();

        $testDoc->setName('nice doc');
        $testDoc->setState('draft');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $testDoc->setState('review');
        $documentManager->flush();

        $testDoc->setState('published');
        $documentManager->flush();

        $this->assertEquals('review', $testDoc->getState());
        $this->assertTrue(isset($this->calls[Events::transitionDenied]));
    }

    public function testReadAccess(){

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $testDoc = new AccessControlled();

        $testDoc->setName('read doc');
        $testDoc->setState('draft');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $documentManager->getRepository(get_class($testDoc))->find($testDoc->getId());
        $this->assertNotNull($testDoc);

        $testDoc->setState('review');
        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $documentManager->getRepository(get_class($testDoc))->find($testDoc->getId());
        $this->assertNull($testDoc);
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}