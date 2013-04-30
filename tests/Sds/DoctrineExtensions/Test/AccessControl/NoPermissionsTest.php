<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Sds\DoctrineExtensions\AccessControl\Events as AccessControlEvents;
use Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document\NoPermissions;
use Sds\DoctrineExtensions\Test\BaseTest;

class NoPermissionsTest extends BaseTest {

    protected $calls = array();

    public function setUp(){
        parent::setUp();

        $this->configIdentity(true);
        $this->identity->addRole('admin');

        $manifest = $this->getManifest(['extensionConfigs' => ['Sds\DoctrineExtensions\AccessControl' => true]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
    }

    public function testNoPermissions(){
        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);

        $testDoc = new NoPermissions();
        $testDoc->setName('nathan');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $documentManager->flush();

        $this->assertTrue(isset($this->calls[AccessControlEvents::createDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}