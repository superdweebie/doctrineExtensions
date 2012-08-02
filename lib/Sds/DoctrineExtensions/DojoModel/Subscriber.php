<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DojoModel;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\ClassNamePropertyTrait;

/**
 * Adds dojoModel values to classmetadata
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
            Sds\DojoDiscriminator::event,
            Sds\DojoClassName::event
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
    public function annotationDojoDiscriminator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadataKey = $annotation::metadataKey;
        $eventArgs->getMetadata()->$metadataKey = (boolean) $annotation->value;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoClassName(AnnotationEventArgs $eventArgs)
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