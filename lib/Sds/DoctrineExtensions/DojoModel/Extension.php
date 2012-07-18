<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DojoModel;

use Sds\DoctrineExtensions\AbstractExtension;
use Sds\DoctrineExtensions\DojoModel\Console\Command\GenerateModelsCommand;
use Sds\DoctrineExtensions\DojoModel\Console\Helper\DestPathHelper;

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

        //$this->subscribers = array(new Subscriber($config->getAnnotationReader()));

        $this->cliCommands = array(new GenerateModelsCommand());

        $this->cliHelpers = array(new DestPathHelper($config->getDestPath()));
    }
}
