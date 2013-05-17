<?php

namespace Sds\DoctrineExtensions\Test\Serializer;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Flavour;

class SerializerCustomTypeSerializerTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.serializer' => [
                    'type_serializers' => [
                        'string' => 'stringTypeSerializer'
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

        $manifest->getServiceManager()->setInvokableClass('stringTypeSerializer', 'Sds\DoctrineExtensions\Test\Serializer\TestAsset\StringSerializer');
        $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
        $this->serializer = $manifest->getServiceManager()->get('serializer');

    }

    public function testSerializer(){

        $flavour = new Flavour('cherry');

        $array = $this->serializer->toArray($flavour, $this->documentManager);

        $this->assertEquals('Cherry', $array['name']);
    }


    public function testApplySerializeMetadataToArray(){

        $array = $this->serializer->ApplySerializeMetadataToArray(
            ['name' => 'cherry'],
            'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Flavour'
        );

        $this->assertEquals('Cherry', $array['name']);
    }

    public function testUnserializer(){

        $data = array(
            'name' => 'Cherry'
        );

        $flavour = $this->serializer->fromArray(
            $data,
            'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Flavour'
        );

        $this->assertEquals('cherry', $flavour->getName());
    }
}