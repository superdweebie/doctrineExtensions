<?php

namespace Sds\DoctrineExtensions\Test\Serializer;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Flavour;

class SerializerCustomTypeSerializerTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest([
            'extensionConfigs' => [
                'Sds\DoctrineExtensions\Serializer' => [
                    'typeSerializers' => [
                        'string' => 'stringTypeSerializer'
                    ],
                    'typeSerializerServiceConfig' => [
                        'invokables' => [
                            'stringTypeSerializer' => 'Sds\DoctrineExtensions\Test\Serializer\TestAsset\StringSerializer'
                        ]
                    ],
                ]
            ]
        ]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
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