<?php

namespace Sds\DoctrineExtensions\Test\DojoModel;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Sds\DoctrineExtensions\DojoModel\DojoModelGenerator;
use Sds\DoctrineExtensions\Test\BaseTest;

class DojoModelTest extends BaseTest {

    protected $generator;

    protected $path;

    public function setUp(){
        parent::setUp();

        $this->path = __DIR__ . '/../../../../DojoModels';

        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\DojoModel' => array(
            'destPaths' => array(
                'filter' => '',
                'path' => $this->path
            )
        )));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\DojoModel\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );

        $this->generator = new DojoModelGenerator();
        $this->generator->setRegenerateDojoModelIfExists(true);
    }

    public function testDojoModelGenerator(){

        $documentManager = $this->documentManager;
        $metadataFactory = new ClassMetadataFactory();
        $metadataFactory->setConfiguration($documentManager->getConfiguration());
        $metadataFactory->setDocumentManager($documentManager);

        $metadatas = $metadataFactory->getAllMetadata();

        $this->generator->generate($metadatas, $this->path);

        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple.js'),
            file_get_contents($this->path . '/Sds/DoctrineExtensions/Test/DojoModel/TestAsset/Document/Simple.js')
        );
    }

    public function tearDown() {
    }
}