<?php

namespace Sds\DoctrineExtensions\Test\Annotation;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document\ChildA;
use Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document\ChildB;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\Identity;

class AnnotationInheritaceTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.dojo' => [
                    'file_paths' => [[
                        'filter' => '',
                        'path' => __DIR__ . '/../../../../Dojo'
                    ]]
                ],
                'extension.serializer' => true,
                'extension.validator' => true,
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                    'identity' => function(){
                        $identity = new Identity();
                        return $identity;
                    }
                ]
            ]
       ]);

       $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');

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