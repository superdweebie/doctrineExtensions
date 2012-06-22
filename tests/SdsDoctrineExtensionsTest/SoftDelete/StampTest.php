<?php

namespace SdsDoctrineExtensionsTest\SoftDelete;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Document\Stamped;
use SdsDoctrineExtensions\SoftDelete\ExtensionConfig;

class StampTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $extensionConfig = new ExtensionConfig();
        $extensionConfig->setUseSoftDeleteStamps(true);
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\SoftDelete' => $extensionConfig));

        $this->configure(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testStamps() {

        $documentManager = $this->documentManager;
        $testDoc = new Stamped();
        $testDoc->setName('version1');

        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc->getSoftDeletedBy());
        $this->assertNull($testDoc->getSoftDeletedOn());
        $this->assertNull($testDoc->getSoftRestoredBy());
        $this->assertNull($testDoc->getSoftRestoredOn());

        $testDoc->softDelete();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('toby', $testDoc->getSoftDeletedBy());
        $this->assertNotNull($testDoc->getSoftDeletedOn());
        $this->assertNull($testDoc->getSoftRestoredBy());
        $this->assertNull($testDoc->getSoftRestoredOn());

        $testDoc->restore();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('toby', $testDoc->getSoftDeletedBy());
        $this->assertNotNull($testDoc->getSoftDeletedOn());
        $this->assertEquals('toby', $testDoc->getSoftRestoredBy());
        $this->assertNotNull($testDoc->getSoftRestoredOn());
    }
}