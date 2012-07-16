<?php

namespace Sds\DoctrineExtensions\Test\DiscriminatorMap;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\DiscriminatorMap\TestAsset\DiscriminatorMap as MapClass;
use Sds\DoctrineExtensions\Test\DiscriminatorMap\TestAsset\Document\DocA;
use Sds\DoctrineExtensions\Test\DiscriminatorMap\TestAsset\Document\DocB;

class DescriminatorMapTest extends BaseTest {

    public function setUp(){

        parent::setUp();

        $this->configActiveUser();

        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\DiscriminatorMap' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\DiscriminatorMap\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testBasicFunction(){

        $documentManager = $this->documentManager;

        $docA = new DocA();
        $docB = new DocB();

        $mapClass = new MapClass();
        $descriminatorMap = $mapClass->getDiscriminatorMap();

        $this->assertEquals(
            $descriminatorMap,
            $documentManager->getClassMetadata(get_class($docA))->discriminatorMap
        );

        $this->assertEquals(
            $descriminatorMap,
            $documentManager->getClassMetadata(get_class($docB))->discriminatorMap
        );
    }
}