<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ServiceLocatorInitalizer implements InitializerInterface {

    public function initialize($instance, ServiceLocatorInterface $serviceLocator){
        if ($instance instanceof ServiceLocatorAwareInterface) {
            $instance->setServiceLocator($serviceLocator);
        }
    }
}