<?php

namespace SdsDoctrineExtensionsTest\Audit;

use SdsDoctrineExtensions\Audit\Event\EventArgs;
use SdsDoctrineExtensions\Audit\Event\Events;
use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\Audit\TestAsset\Document\Simple;

class AuditTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configActiveUser();

        $manifest = $this->getManifest(array('SdsDoctrineExtensions\Audit' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\Audit\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testBasicFunction(){

        $documentManager = $this->documentManager;
        $testDoc = new Simple();

        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('version 1', $testDoc->getName());
        $this->assertCount(0, $testDoc->getAudits());

        $testDoc->setName('version 2');

        $documentManager->flush();
        $documentManager->clear();
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('version 2', $testDoc->getName());
        $this->assertCount(1, $testDoc->getAudits());

        $audit = $testDoc->getAudits()[0];
        $this->assertEquals('version 1', $audit->getOldValue());
        $this->assertEquals('version 2', $audit->getNewValue());
        $this->assertEquals('toby', $audit->getChangedBy());
        $this->assertNotNull($audit->getChangedOn());
    }

    public function testEvents() {

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();
        $eventManager->addEventListener(Events::auditCreated, $this);

        $testDoc = new Simple();

        $testDoc->setName('version 1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $testDoc->setName('version 2');

        $documentManager->flush();

        $this->assertTrue(isset($this->calls[Events::auditCreated]));

        $eventArgs = $this->calls[Events::auditCreated];

        $this->assertTrue($eventArgs instanceof EventArgs);
        $this->assertEquals('version 2', $eventArgs->getAudit()->getNewValue());
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments[0];
    }
}