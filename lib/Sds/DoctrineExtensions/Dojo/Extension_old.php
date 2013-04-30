<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo;

use Sds\DoctrineExtensions\AbstractExtension;
use Sds\DoctrineExtensions\Dojo\Generator;

/**
 * Defines the resouces this extension provies
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    public function __construct($config){

        $this->configClass = __NAMESPACE__ . '\ExtensionConfig';
        parent::__construct($config);
        $config = $this->getConfig();

        $serviceLocator = $config->getServiceLocator();
        $filePaths = $config->getFilePaths();
        $defaultMixins = $config->getDefaultMixins();
        $persistToFile = $config->getPersistToFile();

        $this->subscribers = [
            'Sds\DoctrineExtensions\Dojo\AnnotationSubscriber',
            new Generator\Form(
                $serviceLocator,
                $filePaths,
                $defaultMixins,
                $persistToFile
            ),
            new Generator\Input(
                $serviceLocator,
                $filePaths,
                $defaultMixins,
                $persistToFile
            ),
            new Generator\MultiFieldValidator(
                $serviceLocator,
                $filePaths,
                $defaultMixins,
                $persistToFile
            ),
            new Generator\Validator(
                $serviceLocator,
                $filePaths,
                $defaultMixins,
                $persistToFile
            ),
            new Generator\Model(
                $serviceLocator,
                $filePaths,
                $defaultMixins,
                $persistToFile
            ),
            new Generator\ModelValidator(
                $serviceLocator,
                $filePaths,
                $defaultMixins,
                $persistToFile
            ),
            new Generator\JsonRest(
                $serviceLocator,
                $filePaths,
                $defaultMixins,
                $persistToFile
            )
        ];
    }
}
