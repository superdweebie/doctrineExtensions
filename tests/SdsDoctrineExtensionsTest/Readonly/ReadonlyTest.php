<?php

namespace SdsDoctrineExtensionsTest\Readonly;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensions\Readonly\Subscriber\Readonly as ReadonlySubscriber;
use SdsDoctrineExtensionsTest\Readonly\TestAsset\Document\Simple;
use SdsDoctrineExtensionsTest\Readonly\TestAsset\Document\SetMethod;
use SdsDoctrineExtensionsTest\Readonly\TestAsset\Subscriber;

class ReadonlyTest extends BaseTest {

    public function setUp(){

        parent::setUp();

        $reflection = new \ReflectionClass('\SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly');

        $this->configure(
            array('SdsDoctrineExtensionsTest\Readonly\TestAsset\Document' => __DIR__ . '/TestAsset/Document'),
            array(),
            array(new ReadonlySubscriber($this->annotationReader)),
            array($reflection->getFilename())
        );
    }

    public function testBasicFunction(){

        $documentManager = $this->documentManager;
        $testDoc = new Simple();

        $testDoc->setReadonlyField('cannot-change');
        $testDoc->setMutableField('can-change');

        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('cannot-change', $testDoc->getReadonlyField());
        $this->assertEquals('can-change', $testDoc->getMutableField());

        $testDoc->setReadonlyField('readonly-changed');
        $testDoc->setMutableField('mutable-changed');

        $documentManager->flush();
        $documentManager->clear();
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('cannot-change', $testDoc->getReadonlyField());
        $this->assertEquals('mutable-changed', $testDoc->getMutableField());
    }

    public function testAternateSetMethod() {

        $documentManager = $this->documentManager;
        $testDoc = new SetMethod();

        $testDoc->good('cannot-change');

        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('cannot-change', $testDoc->getGoodField());

        $testDoc->good('changed');
        $documentManager->flush();
        $documentManager->clear();
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('cannot-change', $testDoc->getGoodField());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetMethodNotFound() {

        $documentManager = $this->documentManager;
        $testDoc = new SetMethod();

        $testDoc->bad('cannot-change');

        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('cannot-change', $testDoc->getBadField());

        $testDoc->bad('changed');
        $documentManager->flush();
    }

    public function testEvents() {

        $subscriber = new Subscriber();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();
        $eventManager->addEventSubscriber($subscriber);

        $testDoc = new Simple();

        $testDoc->setReadonlyField('cannot-change');
        $testDoc->setMutableField('can-change');

        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $testDoc->setMutableField('mutable-changed');

        $documentManager->flush();

        $this->assertFalse($subscriber->getPreCalled());
        $this->assertFalse($subscriber->getPostCalled());

        $subscriber->reset();

        $testDoc->setReadonlyField('readonly-changed');

        $documentManager->flush();

        $this->assertTrue($subscriber->getPreCalled());
        $this->assertTrue($subscriber->getPostCalled());

        $subscriber->reset();
        $subscriber->setRestoreInPre(true);

        $testDoc->setReadonlyField('readonly-changed');

        $documentManager->flush();

        $this->assertTrue($subscriber->getPreCalled());
        $this->assertFalse($subscriber->getPostCalled());
    }
}