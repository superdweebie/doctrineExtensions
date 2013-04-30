<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */

namespace Sds\DoctrineExtensions;

use Zend\ServiceManager\Config;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ServiceManagerFactory
{

    public static function create(array $serviceManagerConfig, array $extensionConfigs){

        $serviceManager = new ServiceManager(new Config($serviceManagerConfig));
        $serviceManager->setService('extensionConfigs', $extensionConfigs);

        return $serviceManager;
    }
}
