<?php

namespace Sds\DoctrineExtensions\Test\Dojo;

use Sds\DoctrineExtensions\Generator\Generator;
use Sds\DoctrineExtensions\Test\BaseTest;

class DojoTest extends BaseTest {

    protected $generator;

    protected $path;

    public function setUp(){
        parent::setUp();

        $this->path = __DIR__ . '/../../../../Dojo';

        $manifest = $this->getManifest(['extensionConfigs' => ['Sds\DoctrineExtensions\Dojo' => [
            'persistToFile' => true,
            'filePaths' => [
                ['filter' => '', 'path' => $this->path]
            ]
        ]]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Dojo\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
        $this->generator = $manifest->getServiceManager()->get('generator');
    }

    public function testInputGenerator(){

        $generator = $this->generator;
        $map = $generator->getResourceMap()->getMap();

        foreach ($map as $resourceName => $config){
            $generator->generate($resourceName);
        }

        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple.js'),
            file_get_contents($this->path . '/Sds/DoctrineExtensions/Test/Dojo/TestAsset/Document/Simple.js')
        );
    }

    public function tearDown() {
    }
}