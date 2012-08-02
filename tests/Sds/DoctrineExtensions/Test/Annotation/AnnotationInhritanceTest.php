<?php

namespace Sds\DoctrineExtensions\Test\Annotation;

use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document\ChildA;
use Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document\ChildB;
use Sds\DoctrineExtensions\Test\BaseTest;

class AnnotationInheritaceTest extends BaseTest {

    public function setUp(){
        parent::setUp();

        $this->configActiveUser(true);

        $manifest = $this->getManifest(array(
            'Sds\DoctrineExtensions\DoNotHardDelete' => null,
            'Sds\DoctrineExtensions\DojoModel' => array(
                'destPaths' => array(
                    'filter' => '',
                    'path' => __DIR__ . '/../../../../DojoModels'
            )),
            'Sds\DoctrineExtensions\Serializer' => null,
            'Sds\DoctrineExtensions\Validator' => null,
            'Sds\DoctrineExtensions\Workflow' => null
        ));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testAnnotationInheritance(){

        $documentManager = $this->documentManager;

        $metadata = $documentManager->getClassMetadata(get_class(new ChildA));

        $this->assertTrue($metadata->{Sds\DoNotHardDelete::metadataKey});
        $this->assertEquals('className', $metadata->{Sds\DojoClassName::metadataKey});
        $this->assertTrue($metadata->{Sds\DojoDiscriminator::metadataKey});
        $this->assertEquals('className', $metadata->{Sds\SerializeClassName::metadataKey});
        $this->assertTrue($metadata->{Sds\SerializeDiscriminator::metadataKey});
        $this->assertEquals(array('ParentValidator' => []), $metadata->{Sds\Validator::metadataKey});
        $this->assertEquals('ParentWorkflow', $metadata->{Sds\WorkflowClass::metadataKey});
    }

    public function testAnnotationInheritanceOverride(){

        $documentManager = $this->documentManager;

        $metadata = $documentManager->getClassMetadata(get_class(new ChildB));

        $this->assertFalse($metadata->{Sds\DoNotHardDelete::metadataKey});
        $this->assertFalse($metadata->{Sds\DojoClassName::metadataKey});
        $this->assertFalse($metadata->{Sds\DojoDiscriminator::metadataKey});
        $this->assertFalse($metadata->{Sds\SerializeClassName::metadataKey});
        $this->assertFalse($metadata->{Sds\SerializeDiscriminator::metadataKey});
        $this->assertEquals(array('ChildBValidator' => []), $metadata->{Sds\Validator::metadataKey});
        $this->assertEquals('ChildBWorkflow', $metadata->{Sds\WorkflowClass::metadataKey});
    }
}