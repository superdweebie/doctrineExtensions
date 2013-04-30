<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AbstractExtensionFactory implements AbstractFactoryInterface
{

    protected $extensionConfigs;

    protected $extensions;

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName){

        if (substr_compare($requestedName, '\Extension', -10, 10) === 0){
            return true;
        }
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName){

        $name = substr($requestedName, 0, -10);

        if (isset($this->extensions[$name])){
            return $this->extensions[$name];
        }

        $extensionConfigs = $this->getExtensionConfigs($serviceLocator);

        if ( ! isset($extensionConfigs[$name]) || is_bool($extensionConfigs[$name])){
            $extensionConfig = [];
        } else {
            $extensionConfig = $extensionConfigs[$name];
        }

        $extensionClass = $requestedName;
        $extension = new $extensionClass($extensionConfig);

        $this->extensions[$name] = $extension;
        return $extension;
    }

    protected function getExtensionConfigs($serviceLocator){
        if (!isset($this->extensionConfigs)){
            $this->extensionConfigs = $serviceLocator->get('extensionConfigs');
        }
        return $this->extensionConfigs;
    }
}
