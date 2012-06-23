<?php

namespace SdsDoctrineExtensionsTest\Audit;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\Audit\TestAsset\Document\Simple;
use SdsDoctrineExtensionsTest\Audit\TestAsset\Subscriber;

class AuditTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\Audit' => null));

        $this->configure(
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

        $this->markTestIncomplete();
        
        $documentManager = $this->documentManager;
        $testDoc = new Simple();

        $testDoc->setName('version 1');

        $id = $this->persist($testDoc);

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

//        $subscriber = new Subscriber();
//
//        $documentManager = $this->documentManager;
//        $eventManager = $documentManager->getEventManager();
//        $eventManager->addEventSubscriber($subscriber);
//
//        $testDoc = new Simple();
//
//        $testDoc->setReadonlyField('cannot-change');
//        $testDoc->setMutableField('can-change');
//
//        $id = $this->persist($testDoc);
//
//        $repository = $documentManager->getRepository(get_class($testDoc));
//        $testDoc = null;
//        $testDoc = $repository->find($id);
//
//        $testDoc->setMutableField('mutable-changed');
//
//        $documentManager->flush();
//
//        $this->assertFalse($subscriber->getPreCalled());
//        $this->assertFalse($subscriber->getPostCalled());
//
//        $subscriber->reset();
//
//        $testDoc->setReadonlyField('readonly-changed');
//
//        $documentManager->flush();
//
//        $this->assertTrue($subscriber->getPreCalled());
//        $this->assertTrue($subscriber->getPostCalled());
//
//        $subscriber->reset();
//        $subscriber->setRestoreInPre(true);
//
//        $testDoc->setReadonlyField('readonly-changed');
//
//        $documentManager->flush();
//
//        $this->assertTrue($subscriber->getPreCalled());
//        $this->assertFalse($subscriber->getPostCalled());
    }
}