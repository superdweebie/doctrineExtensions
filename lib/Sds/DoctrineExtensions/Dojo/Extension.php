<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo;

use Sds\DoctrineExtensions\AbstractExtension;
use Sds\DoctrineExtensions\Dojo\Console\Command\GenerateCommand;
use Sds\DoctrineExtensions\Dojo\Console\Helper\DestPathsHelper;

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

        $this->subscribers = array(new Subscriber(
            $config->getAnnotationReader(),
            $config->getClassNameProperty()
        ));

        $this->cliCommands = array(new GenerateCommand());

        $this->cliHelpers = array('destPaths' => new DestPathsHelper($config->getDestPaths()));
    }
}
