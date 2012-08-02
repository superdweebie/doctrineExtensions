<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\ClassNamePropertyTrait;

/**
 * Adds serializer values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber, AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;
    use ClassNamePropertyTrait;

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            Sds\DoNotSerialize::event,
            Sds\SerializeDiscriminator::event,
            Sds\SerializeClassName::event
        );
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param string $className
     */
    public function __construct(Reader $annotationReader, $classNameProperty){
        $this->setAnnotationReader($annotationReader);
        $this->setClassNameProperty($classNameProperty);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDoNotSerialize(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $eventArgs->getMetadata()->fieldMappings[$eventArgs->getReflection()->getName()][$annotation::metadataKey] = true;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializeDiscriminator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadataKey = $annotation::metadataKey;
        $eventArgs->getMetadata()->$metadataKey = (boolean) $annotation->value;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializeClassName(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadataKey = $annotation::metadataKey;
        if ($annotation->value) {
            $eventArgs->getMetadata()->$metadataKey = $this->classNameProperty;
        } else {
            $eventArgs->getMetadata()->$metadataKey = false;
        }
    }
}