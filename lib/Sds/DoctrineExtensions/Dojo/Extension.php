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

    protected $filePaths;

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

    protected $subscribers = [
        'Sds\DoctrineExtensions\Dojo\AnnotationSubscriber',
        'Sds\DoctrineExtensions\Dojo\Generator\Form',
        'Sds\DoctrineExtensions\Dojo\Generator\Input',
        'Sds\DoctrineExtensions\Dojo\Generator\MultiFieldValidator',
        'Sds\DoctrineExtensions\Dojo\Generator\Validator',
        'Sds\DoctrineExtensions\Dojo\Generator\Model',
        'Sds\DoctrineExtensions\Dojo\Generator\ModelValidator',
        'Sds\DoctrineExtensions\Dojo\Generator\JsonRest',
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

    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'Sds\DoctrineExtensions\Generator' => true,
        'Sds\DoctrineExtensions\Rest' => true,
        'Sds\DoctrineExtensions\Serializer' => true,
        'Sds\DoctrineExtensions\Validator' => true,
    );
}