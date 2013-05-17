<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'subscriber.dojo.annotationSubscriber',
        'subscriber.dojo.generator.form',
        'subscriber.dojo.generator.input',
        'subscriber.dojo.generator.multifieldvalidator',
        'subscriber.dojo.generator.validator',
        'subscriber.dojo.generator.model',
        'subscriber.dojo.generator.modelvalidator',
        'subscriber.dojo.generator.jsonrest',
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.dojo.annotationSubscriber' => 'Sds\DoctrineExtensions\Dojo\AnnotationSubscriber',
            'subscriber.dojo.generator.form' => 'Sds\DoctrineExtensions\Dojo\Generator\Form',
            'subscriber.dojo.generator.input' => 'Sds\DoctrineExtensions\Dojo\Generator\Input',
            'subscriber.dojo.generator.multifieldvalidator' => 'Sds\DoctrineExtensions\Dojo\Generator\MultiFieldValidator',
            'subscriber.dojo.generator.validator' => 'Sds\DoctrineExtensions\Dojo\Generator\Validator',
            'subscriber.dojo.generator.model' => 'Sds\DoctrineExtensions\Dojo\Generator\Model',
            'subscriber.dojo.generator.modelvalidator' => 'Sds\DoctrineExtensions\Dojo\Generator\ModelValidator',
            'subscriber.dojo.generator.jsonrest' => 'Sds\DoctrineExtensions\Dojo\Generator\JsonRest',
        ]
    ];

    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'extension.generator' => true,
        'extension.rest' => true,
        'extension.serializer' => true,
        'extension.validator' => true,
    );

    protected $filePaths = [];

    protected $defaultMixins = [
        'model'                    => ['Sds/Mvc/BaseModel'],
        'form' => [
            'simple'               => ['Sds/Form/Form'],
            'withValidator'        => ['Sds/Form/ValidationControlGroup'],
        ],
        'input' => [
            'string'               => ['Sds/Form/TextBox'],
            'stringWithValidator'  => ['Sds/Form/ValidationTextBox'],
            'float'                => ['Sds/Form/TextBox'],
            'floatWithValidator'   => ['Sds/Form/ValidationTextBox'],
            'int'                  => ['Sds/Form/TextBox'],
            'intWithValidator'     => ['Sds/Form/ValidationTextBox'],
            'boolean'              => ['Sds/Form/Checkbox'],
        ],
        'validator' => [
            'model'                => ['Sds/Validator/Model'],
            'group'                => ['Sds/Validator/Group']
        ],
        'store' => [
            'jsonRest'             => ['Sds/Mvc/JsonRest']
        ]
    ];
    
    protected $persistToFile = false;

    /**
     *
     * @return string
     */
    public function getFilePaths() {
        return $this->filePaths;
    }

    /**
     *
     * @param array $filePaths
     */
    public function setFilePaths(array $filePaths) {
        $this->filePaths = $filePaths;
    }

    public function getDefaultMixins() {
        return $this->defaultMixins;
    }

    public function setDefaultMixins(array $defaultMixins) {
        $this->defaultMixins = $defaultMixins;
    }

    public function getPersistToFile() {
        return $this->persistToFile;
    }

    public function setPersistToFile($persistToFile) {
        $this->persistToFile = $persistToFile;
    }
}