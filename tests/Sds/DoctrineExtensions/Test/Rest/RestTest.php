<?php

namespace Sds\DoctrineExtensions\Test\Readonly;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Rest\TestAsset\Document\Explicit;
use Sds\DoctrineExtensions\Test\Rest\TestAsset\Document\Implicit;

class RestTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(['extensionConfigs' => ['Sds\DoctrineExtensions\Rest' => true]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Rest\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
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