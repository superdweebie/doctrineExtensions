<?php

namespace Sds\DoctrineExtensions\Test\Rest;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Rest\TestAsset\Document\Explicit;
use Sds\DoctrineExtensions\Test\Rest\TestAsset\Document\Implicit;

class RestTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.rest' => true
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                ]
            ]
        ]);

        $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
    }

    public function testExplicit(){

        $documentManager = $this->documentManager;

        $metadata = $documentManager->getClassMetadata(get_class(new Explicit));

        $this->assertEquals('RestAPI/Explicit', $metadata->rest['endpoint']);
    }

    public function testImplicit(){

        $documentManager = $this->documentManager;

        $metadata = $documentManager->getClassMetadata(get_class(new Implicit));

        $this->assertEquals('implicit', $metadata->rest['endpoint']);
    }
}