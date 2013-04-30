<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SerializerFactory implements FactoryInterface
{

    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return object
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $extension = $serviceLocator->get('Sds\DoctrineExtensions\Serializer\Extension');
        $instance = new Serializer;

        $instance->setClassNameField($extension->getClassNameField());
        $instance->setMaxNestingDepth($extension->getMaxNestingDepth());
        $instance->setTypeSerializers($extension->getTypeSerializers());
        $instance->setReferenceSerializerServiceConfig($extension->getReferenceSerializerServiceConfig());
        $instance->setTypeSerializerServiceConfig($extension->getTypeSerializerServiceConfig());

        return $instance;
    }
}
