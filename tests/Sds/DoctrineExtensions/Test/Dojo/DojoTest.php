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
                    'flat_file_strategy' => 'save',
                    'file_paths' => [[
                        'filter' => '',
                        'path' => $this->path
                    ]]
                ],
                'extension.generator' => [
                    'resource_map' => [
                        'Sds/Simple.js' => [
                            'generator' => 'generator.dojo.model',
                            'class'     => __NAMESPACE__ . '\TestAsset\Document\Simple'
                        ],
                        'Sds/Simple/NameInput.js' => [
                            'generator'       => 'generator.dojo.input',
                            'class'           => __NAMESPACE__ . '\TestAsset\Document\Simple',
                            'options'         => [
                                'field'       => 'name',
                                'params'      => [
                                    'label'       => 'NAME',
                                    'tooltip'     => 'The document name',
                                    'description' => 'This is a longer description'
                                ]
                            ]
                        ],
                        'Sds/Simple/Form.js' => [
                            'generator'       => 'generator.dojo.form',
                            'class'           => __NAMESPACE__ . '\TestAsset\Document\Simple',
                        ],
                        'Sds/Simple/Store.js' => [
                            'generator'       => 'generator.dojo.jsonrest',
                            'class'           => __NAMESPACE__ . '\TestAsset\Document\Simple',
                        ],
                        'Sds/Simple/ModelValidator.js' => [
                            'generator'       => 'generator.dojo.modelvalidator',
                            'class'           => __NAMESPACE__ . '\TestAsset\Document\Simple',
                        ],
                        'Sds/Simple/MultiFieldValidator.js' => [
                            'generator'       => 'generator.dojo.multifieldvalidator',
                            'class'           => __NAMESPACE__ . '\TestAsset\Document\Simple',
                        ],
                        'Sds/Simple/NameValidator.js' => [
                            'generator'       => 'generator.dojo.validator',
                            'class'           => __NAMESPACE__ . '\TestAsset\Document\Simple',
                            'options'         => [
                                'field'       => 'name',
                            ]
                        ]
                    ]
                ]
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                ]
            ]
       ]);

       $this->resourceMap = $manifest->getServiceManager()->get('resourceMap');
    }

    public function testInputGenerator(){
        $this->resourceMap->get('Sds/Simple/NameInput.js');
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple/NameInput.js'),
            file_get_contents($this->path . '/Sds/Simple/NameInput.js')
        );
    }

    public function testFormGenerator(){
        $this->resourceMap->get('Sds/Simple/Form.js');
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple/Form.js'),
            file_get_contents($this->path . '/Sds/Simple/Form.js')
        );
    }

    public function testModelGenerator(){
        $this->resourceMap->get('Sds/Simple.js');
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple.js'),
            file_get_contents($this->path . '/Sds/Simple.js')
        );
    }

    public function testJsonRestGenerator(){
        $this->resourceMap->get('Sds/Simple/Store.js');
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple/Store.js'),
            file_get_contents($this->path . '/Sds/Simple/Store.js')
        );
    }

    public function testModelValidatorGenerator(){
        $this->resourceMap->get('Sds/Simple/ModelValidator.js');
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple/ModelValidator.js'),
            file_get_contents($this->path . '/Sds/Simple/ModelValidator.js')
        );
    }

    public function testMultiFieldValidatorGenerator(){
        $this->resourceMap->get('Sds/Simple/MultiFieldValidator.js');
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple/MultiFieldValidator.js'),
            file_get_contents($this->path . '/Sds/Simple/MultiFieldValidator.js')
        );
    }

    public function testValidatorGenerator(){
        $this->resourceMap->get('Sds/Simple/NameValidator.js');
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAsset/Simple/NameValidator.js'),
            file_get_contents($this->path . '/Sds/Simple/NameValidator.js')
        );
    }
}