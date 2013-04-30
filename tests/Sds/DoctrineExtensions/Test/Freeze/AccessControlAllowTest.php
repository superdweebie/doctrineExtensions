<?php

namespace Sds\DoctrineExtensions\Test\Freeze;

use Sds\DoctrineExtensions\Freeze\Events;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document\AccessControlled;

class AccessControlAllowTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configIdentity(true);
        $this->identity->addRole('admin');

        $manifest = $this->getManifest(['extensionConfigs' => [
            'Sds\DoctrineExtensions\Freeze' => true,
            'Sds\DoctrineExtensions\AccessControl' => true
        ]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
        $this->freezer = $manifest->getServiceManager()->get('freezer');
    }

    public function testFreezeAllow(){

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

        $this->freezer->freeze($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertTrue($this->freezer->isFrozen($testDoc));
        $this->assertFalse(isset($this->calls[Events::freezeDenied]));
    }

    public function testThawGrant(){

        $this->calls = array();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::thawDenied, $this);

        $testDoc = new AccessControlled();
        $this->freezer->freeze($testDoc);
        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->freezer->thaw($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);
        $this->assertFalse($this->freezer->isFrozen($testDoc));
        $this->assertFalse(isset($this->calls[Events::thawDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}