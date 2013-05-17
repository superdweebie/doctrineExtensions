<?php

namespace Sds\DoctrineExtensions\Test\Dojo;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;

class DojoTest extends BaseTest {

    protected $generator;

    protected $path;

    public function setUp(){

        $this->path = __DIR__ . '/../../../../Dojo';

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.dojo' => [
                    'persist_to_file' => true,
                    'file_paths' => [[
                        'filter' => '',
                        'path' => $this->path
                    ]]
                ],
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                ]
            ]
       ]);

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