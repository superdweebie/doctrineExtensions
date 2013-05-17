<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Generator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class GeneratorFactory implements FactoryInterface
{

    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return object
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $instance = new Generator;
        $instance->setResourceMap($serviceLocator->get('resourceMap'));

        return $instance;
    }
}
