<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class LazySubscriberFactory implements FactoryInterface
{

    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return object
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new LazySubscriber();

        $instance->setConfig($serviceLocator->get('manifest')['lazy_subscriber_config']);
        return $instance;
    }
}
