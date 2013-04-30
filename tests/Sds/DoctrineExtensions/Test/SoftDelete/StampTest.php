<?php

namespace Sds\DoctrineExtensions\Test\SoftDelete;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\SoftDelete\TestAsset\Document\Stamped;

class StampTest extends BaseTest {

    public function setUp(){

        parent::setUp();

        $this->configIdentity();

        $manifest = $this->getManifest(['extensionConfigs' => ['Sds\DoctrineExtensions\SoftDelete' => true]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\SoftDelete\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
        $this->softDeleter = $manifest->getServiceManager()->get('softDeleter');
    }

    public function testStamps() {

        $documentManager = $this->documentManager;
        $testDoc = new Stamped();
        $testDoc->setName('version1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc->getSoftDeletedBy());
        $this->assertNull($testDoc->getSoftDeletedOn());
        $this->assertNull($testDoc->getRestoredBy());
        $this->assertNull($testDoc->getRestoredOn());

        $this->softDeleter->softDelete($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('toby', $testDoc->getSoftDeletedBy());
        $this->assertNotNull($testDoc->getSoftDeletedOn());
        $this->assertNull($testDoc->getRestoredBy());
        $this->assertNull($testDoc->getRestoredOn());

        $this->softDeleter->restore($testDoc);

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('toby', $testDoc->getSoftDeletedBy());
        $this->assertNotNull($testDoc->getSoftDeletedOn());
        $this->assertEquals('toby', $testDoc->getRestoredBy());
        $this->assertNotNull($testDoc->getRestoredOn());
    }
}