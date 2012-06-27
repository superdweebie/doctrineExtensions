<?php

namespace SdsDoctrineExtensionsTest\DoNotHardDelete;

use SdsDoctrineExtensions\DoNotHardDelete\Event\Events;
use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\DoNotHardDelete\TestAsset\Document\Deleteable;
use SdsDoctrineExtensionsTest\DoNotHardDelete\TestAsset\Document\NotDeleteable;

class DoNotHardDeleteTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\DoNotHardDelete' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\DoNotHardDelete\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testAllowDelete(){

        $documentManager = $this->documentManager;

        $testDoc = new Deleteable();
        $testDoc->setName('go');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('go', $testDoc->getName());

        $documentManager->remove($testDoc);
        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc);
    }

    public function testDenyDelete(){

        $documentManager = $this->documentManager;

        $testDoc = new NotDeleteable();
        $testDoc->setName('stay');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('stay', $testDoc->getName());

        $documentManager->remove($testDoc);
        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('stay', $testDoc->getName());
    }

    public function testEventCalled() {

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();
        $eventManager->addEventListener(Events::hardDeleteDenied, $this);

        $testDoc = new NotDeleteable();
        $testDoc->setName('stay');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $documentManager->remove($testDoc);

        $this->assertFalse(isset($this->calls[Events::hardDeleteDenied]));
        $documentManager->flush();
        $this->assertTrue(isset($this->calls[Events::hardDeleteDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}