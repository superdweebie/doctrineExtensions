<?php

namespace Sds\DoctrineExtensions\Test\Rest;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;

class RestTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.rest' => [
                    'endpoint_map' => [
                        'simple' => [
                            'class' => 'Sds\DoctrineExtensions\Test\Rest\TestAsset\Document\Simple',
                            'cache' => [
                                'public'  => true,
                                'max_age' => 10
                            ]
                        ]
                    ]
                ]
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                ]
            ]
        ]);

        $this->endpointMap = $manifest->getServiceManager()->get('endpointmap');
    }

    public function testHas(){

        $this->assertTrue($this->endpointMap->has('simple'));
        $this->assertFalse($this->endpointMap->has('does not exist'));
    }

    public function testGetClass(){
        $this->assertEquals('Sds\DoctrineExtensions\Test\Rest\TestAsset\Document\Simple', $this->endpointMap->getClass('simple'));
    }

    public function testGetCacheOptions(){
        $cacheOptions = $this->endpointMap->getCacheOptions('simple');
        $this->assertTrue($cacheOptions->getPublic());
        $this->assertEquals(10, $cacheOptions->getMaxAge());
    }

    public function testGetEndpoints(){
        $this->assertEquals(['simple'], $this->endpointMap->getEndpoints('Sds\DoctrineExtensions\Test\Rest\TestAsset\Document\Simple'));
    }
}