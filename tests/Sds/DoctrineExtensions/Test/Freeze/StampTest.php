<?php

namespace Sds\DoctrineExtensions\Test\Freeze;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document\Stamped;
use Sds\DoctrineExtensions\Freeze\ExtensionConfig;

class StampTest extends BaseTest {

    public function setUp(){

        parent::setUp();

        $this->configActiveUser();

        $extensionConfig = new ExtensionConfig();
        $extensionConfig->setUseFreezeStamps(true);
        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\Freeze' => $extensionConfig));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
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

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

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