<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Sds\DoctrineExtensions\AccessControl\Events as AccessControlEvents;
use Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document\Simple;
use Sds\DoctrineExtensions\Test\BaseTest;

class SimpleAllTest extends BaseTest {

    protected $calls = array();

    public function setUp(){
        parent::setUp();

        $this->configIdentity(false);

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

    public function testCreateDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);

        $testDoc = new Simple();

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();

        $this->assertTrue(isset($this->calls[AccessControlEvents::createDenied]));

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc);
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}