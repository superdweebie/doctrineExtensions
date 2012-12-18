<?php

namespace Sds\DoctrineExtensions\Test\Serializer;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Serializer\Serializer;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Flavour;

class SerializerCustomTypeSerializerTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\Serializer' => [
            'typeSerializers' => [
                'string' => 'Sds\DoctrineExtensions\Test\Serializer\TestAsset\StringSerializer'
            ]
        ]));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
    }

    public function tearDown() {
        Serializer::removeTypeSerializer('string');
        parent::tearDown();
    }
    
    public function testSerializer(){

        $flavour = new Flavour('cherry');

        $array = Serializer::toArray($flavour, $this->documentManager);

        $this->assertEquals('Cherry', $array['name']);
    }


    public function testApplySerializeMetadataToArray(){

        $array = Serializer::ApplySerializeMetadataToArray(
            ['name' => 'cherry'],
            'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Flavour',
            $this->documentManager
        );

        $this->assertEquals('Cherry', $array['name']);
    }

    public function testUnserializer(){

        $data = array(
            'name' => 'Cherry'
        );

        $flavour = Serializer::fromArray(
            $data,
            $this->documentManager,
            null,
            'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Flavour'
        );

        $this->assertEquals('cherry', $flavour->getName());
    }
}