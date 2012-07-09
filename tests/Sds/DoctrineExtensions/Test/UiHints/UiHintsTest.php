<?php

namespace Sds\DoctrineExtensions\Test\UiHints;

use Sds\DoctrineExtensions\Test\BaseTest;

class UiHintsTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\UiHints' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\UiHints\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testBasicFunction(){

        $documentManager = $this->documentManager;
        $metadata = $documentManager->getClassMetadata('Sds\DoctrineExtensions\Test\UiHints\TestAsset\Document\Simple');

        $idMetadata = $metadata->fieldMappings['id'];
        $nameMetadata = $metadata->fieldMappings['name'];

        $this->assertTrue(isset($idMetadata['uiHints']));
        $this->assertTrue($idMetadata['uiHints']['hidden']);

        $this->assertTrue(isset($nameMetadata['uiHints']));
        $this->assertFalse($nameMetadata['uiHints']['hidden']);
        $this->assertEquals('Simple Name', $nameMetadata['uiHints']['label']);
        $this->assertEquals(20, $nameMetadata['uiHints']['width']);
        $this->assertEquals('Simple document name', $nameMetadata['uiHints']['tooltip']);
        $this->assertEquals('Simple document description', $nameMetadata['uiHints']['description']);
    }
}