<?php

namespace Sds\DoctrineExtensions\Test\Annotation;

use Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document\ChildA;
use Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document\ChildB;
use Sds\DoctrineExtensions\Test\BaseTest;

class AnnotationInheritaceTest extends BaseTest {

    public function setUp(){
        parent::setUp();

        $this->configIdentity(true);

        $manifest = $this->getManifest(['extensionConfigs' => [
            'Sds\DoctrineExtensions\Dojo' => [
                'filePaths' => [
                    'filter' => '',
                    'path' => __DIR__ . '/../../../../Dojo'
            ]],
            'Sds\DoctrineExtensions\Serializer' => true,
            'Sds\DoctrineExtensions\Validator' => true
        ]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
    }

    public function testAnnotationInheritance(){

        $documentManager = $this->documentManager;

        $metadata = $documentManager->getClassMetadata(get_class(new ChildA));

        $this->assertTrue($metadata->serializer['className']);
        $this->assertTrue($metadata->serializer['discriminator']);
        $this->assertEquals([['class' => 'ParentValidator', 'options' => []]], $metadata->validator['document']);
        $this->assertEquals('ignore_always', $metadata->serializer['fields']['name']['ignore']);
    }

    public function testAnnotationInheritanceOverride(){

        $documentManager = $this->documentManager;

        $metadata = $documentManager->getClassMetadata(get_class(new ChildB));

        $this->assertFalse($metadata->serializer['className']);
        $this->assertFalse($metadata->serializer['discriminator']);
        $this->assertEquals([['class' => 'ChildBValidator', 'options' => []]], $metadata->validator['document']);
        $this->assertEquals('none', $metadata->serializer['fields']['name']['ignore']);
    }
}