<?php

namespace Sds\DoctrineExtensions\Test\Readonly;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Rest\TestAsset\Document\Explicit;
use Sds\DoctrineExtensions\Test\Rest\TestAsset\Document\Implicit;

class RestTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(['Sds\DoctrineExtensions\Rest' =>
            ['basePath' => 'http://myserver.com/']
        ]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Rest\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
    }

    public function testExplicit(){

        $documentManager = $this->documentManager;

        $metadata = $documentManager->getClassMetadata(get_class(new Explicit));

        $this->assertEquals('http://myserver.com/RestAPI/Explicit', $metadata->rest['url']);
    }

    public function testImplicit(){

        $documentManager = $this->documentManager;

        $metadata = $documentManager->getClassMetadata(get_class(new Implicit));

        $this->assertEquals('http://myserver.com/Implicit', $metadata->rest['url']);
    }
}