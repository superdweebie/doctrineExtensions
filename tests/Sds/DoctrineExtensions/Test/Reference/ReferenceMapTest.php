<?php

namespace Sds\DoctrineExtensions\Test\Reference;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;

class ReferenceMapTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.reference' => true
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                ]
            ]
        ]);

        $this->referenceMap = $manifest->getServiceManager()->get('referenceMap');
    }

    public function testReferenceMap(){

        $map = $this->referenceMap->getMap();

        $this->assertCount(1, $map['Sds\DoctrineExtensions\Test\Reference\TestAsset\Document\Country']);
        $this->assertEquals('country', $map['Sds\DoctrineExtensions\Test\Reference\TestAsset\Document\Country'][0]['field']);
    }
}