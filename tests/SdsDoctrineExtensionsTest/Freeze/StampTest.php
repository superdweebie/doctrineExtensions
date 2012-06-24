<?php

namespace SdsDoctrineExtensionsTest\Freeze;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\Freeze\TestAsset\Document\Stamped;
use SdsDoctrineExtensions\Freeze\ExtensionConfig;

class StampTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $extensionConfig = new ExtensionConfig();
        $extensionConfig->setUseFreezeStamps(true);
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\Freeze' => $extensionConfig));

        $this->configure(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\Freeze\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
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

        $this->assertNull($testDoc->getFrozenBy());
        $this->assertNull($testDoc->getFrozenOn());
        $this->assertNull($testDoc->getThawedBy());
        $this->assertNull($testDoc->getThawedOn());

        $testDoc->freeze();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('toby', $testDoc->getFrozenBy());
        $this->assertNotNull($testDoc->getFrozenOn());
        $this->assertNull($testDoc->getThawedBy());
        $this->assertNull($testDoc->getThawedOn());

        $testDoc->thaw();

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('toby', $testDoc->getFrozenBy());
        $this->assertNotNull($testDoc->getFrozenOn());
        $this->assertEquals('toby', $testDoc->getThawedBy());
        $this->assertNotNull($testDoc->getThawedOn());
    }
}