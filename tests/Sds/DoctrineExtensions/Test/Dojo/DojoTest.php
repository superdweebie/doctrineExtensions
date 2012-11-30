<?php

namespace Sds\DoctrineExtensions\Test\Dojo;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Sds\DoctrineExtensions\Dojo\DojoGenerator;
use Sds\DoctrineExtensions\Test\BaseTest;

class DojoTest extends BaseTest {

    protected $generator;

    protected $path;

    public function setUp(){
        parent::setUp();

        $this->path = __DIR__ . '/../../../../Dojo';

        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\Dojo' => array(
            'destPaths' => array(
                'filter' => '',
                'path' => $this->path
            )
        )));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Dojo\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );

        $this->generator = new DojoGenerator();
    }

    public function testDojoGenerator(){

        $documentManager = $this->documentManager;
        $metadataFactory = new ClassMetadataFactory();
        $metadataFactory->setConfiguration($documentManager->getConfiguration());
        $metadataFactory->setDocumentManager($documentManager);

        $metadatas = $metadataFactory->getAllMetadata();

        $this->generator->generate($metadatas, $this->path);

        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple.js'),
            file_get_contents($this->path . '/Sds/DoctrineExtensions/Test/Dojo/TestAsset/Document/Simple.js')
        );
    }

    public function tearDown() {
    }
}