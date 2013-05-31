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

    protected $serviceManagerConfig = [
        'invokables' => [
            'cli.command.dojo.files.saveall' => 'Sds\DoctrineExtensions\Dojo\Console\Command\FilesSaveAllCommand',
            'cli.command.dojo.files.deleteall' => 'Sds\DoctrineExtensions\Dojo\Console\Command\FilesDeleteAllCommand',
            'generator.dojo.form' => 'Sds\DoctrineExtensions\Dojo\FormGenerator',
            'generator.dojo.input' => 'Sds\DoctrineExtensions\Dojo\InputGenerator',
            'generator.dojo.multifieldvalidator' => 'Sds\DoctrineExtensions\Dojo\MultiFieldValidatorGenerator',
            'generator.dojo.validator' => 'Sds\DoctrineExtensions\Dojo\ValidatorGenerator',
            'generator.dojo.model' => 'Sds\DoctrineExtensions\Dojo\ModelGenerator',
            'generator.dojo.modelvalidator' => 'Sds\DoctrineExtensions\Dojo\ModelValidatorGenerator',
            'generator.dojo.jsonrest' => 'Sds\DoctrineExtensions\Dojo\JsonRestGenerator'
        ],
        'factories' => [
            'cli.helper.dojo.servicelocator' => 'Sds\DoctrineExtensions\Dojo\Console\Helper\ServiceLocatorHelperFactory',
        ]
    ];

    protected $cliCommands = [
        'cli.command.dojo.files.saveall',
        'cli.command.dojo.files.deleteall'
    ];

    protected $cliHelpers = [
        'servicelocator' => 'cli.helper.dojo.servicelocator',
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

    /**
     * Values can be save | delete | ignore
     *
     * @var string
     */
    protected $flatFileStrategy = 'ignore';

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

    public function getFlatFileStrategy() {
        return $this->flatFileStrategy;
    }

    public function setFlatFileStrategy($flatFileStrategy) {
        $this->flatFileStrategy = (string) $flatFileStrategy;
    }
}