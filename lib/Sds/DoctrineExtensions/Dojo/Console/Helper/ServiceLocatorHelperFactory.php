<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo\Console\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ServiceLocatorHelperFactory implements FactoryInterface
{

    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return object
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ServiceLocatorHelper($serviceLocator);
    }
}
