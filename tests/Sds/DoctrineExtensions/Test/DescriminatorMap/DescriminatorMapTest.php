<?php

namespace Sds\DoctrineExtensions\Test\DescriminatorMap;

use Sds\DoctrineExtensions\DescriminatorMap\EventArgs;
use Sds\DoctrineExtensions\DescriminatorMap\Events;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\DescriminatorMap\TestAsset\DescriminatorMap as MapClass;
use Sds\DoctrineExtensions\Test\DescriminatorMap\TestAsset\Document\DocA;
use Sds\DoctrineExtensions\Test\DescriminatorMap\TestAsset\Document\DocB;

class DescriminatorMapTest extends BaseTest {

    public function setUp(){

        parent::setUp();

        $this->configActiveUser();

        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\DescriminatorMap' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\DescriminatorMap\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
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
        $descriminatorMap = $mapClass->getDescriminatorMap();
        
        $this->assertEquals(
            $descriminatorMap, 
            $documentManager->getClassMetadata(get_class($docA))->getDescriminatorMap()
        );
            
    }  
}