<?php

namespace Sds\DoctrineExtensions\Test\Readonly;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Readonly\Events;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Readonly\TestAsset\Document\Simple;
use Sds\DoctrineExtensions\Test\Readonly\TestAsset\Subscriber;

class ReadonlyTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.readonly' => true
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                ]
            ]
        ]);

        $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
    }

    public function testBasicFunction(){

        $documentManager = $this->documentManager;
        $testDoc = new Simple();

        $testDoc->setReadonlyField('cannot-change');
        $testDoc->setMutableField('can-change');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

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

    public function testEvents() {

        $subscriber = new Subscriber();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();
        $eventManager->addEventSubscriber($subscriber);

        $testDoc = new Simple();

        $testDoc->setReadonlyField('cannot-change');
        $testDoc->setMutableField('can-change');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $testDoc->setMutableField('mutable-changed');

        $documentManager->flush();

        $calls = $subscriber->getCalls();
        $this->assertFalse(isset($calls[Events::preReadonlyRollback]));
        $this->assertFalse(isset($calls[Events::postReadonlyRollback]));

        $subscriber->reset();

        $testDoc->setReadonlyField('readonly-changed');

        $documentManager->flush();

        $calls = $subscriber->getCalls();
        $this->assertTrue(isset($calls[Events::preReadonlyRollback]));
        $this->assertTrue(isset($calls[Events::postReadonlyRollback]));

        $subscriber->reset();
        $subscriber->setRollbackInPre(true);

        $testDoc->setReadonlyField('readonly-changed');

        $documentManager->flush();

        $calls = $subscriber->getCalls();
        $this->assertTrue(isset($calls[Events::preReadonlyRollback]));
        $this->assertFalse(isset($calls[Events::postReadonlyRollback]));
    }
}