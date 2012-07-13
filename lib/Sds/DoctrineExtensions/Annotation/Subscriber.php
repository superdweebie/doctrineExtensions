<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber, AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            ODMEvents::loadClassMetadata
        );
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param \Sds\Common\Validator\ValidatorInterface $validator
     */
    public function __construct(Reader $annotationReader){
        $this->setAnnotationReader($annotationReader);
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $reflClass = $metadata->getReflectionClass();
        $eventManager = $eventArgs->getDocumentManager()->getEventManager();

        //Document annotations
        foreach ($this->annotationReader->getClassAnnotations($reflClass) as $annotation) {
            if (defined(get_class($annotation) . '::event')) {

                // Raise annotation event
                if ($eventManager->hasListeners($annotation::event)) {
                    $eventManager->dispatchEvent(
                        $annotation::event,
                        new AnnotationEventArgs($metadata, EventType::document, $annotation, $reflClass)
                    );
                }
            }
        }

        //Property annotations
        foreach ($reflClass->getProperties() as $reflProperty) {
            if ($metadata->isMappedSuperclass &&
                !$reflProperty->isPrivate() ||
                $metadata->isInheritedField($reflProperty->name)
            ) {
                continue;
            }

            foreach ($this->annotationReader->getPropertyAnnotations($reflProperty) as $annotation) {
                if (defined(get_class($annotation) . '::event')) {

                    // Raise annotation event
                    if ($eventManager->hasListeners($annotation::event)) {
                        $eventManager->dispatchEvent(
                            $annotation::event,
                            new AnnotationEventArgs($metadata, EventType::property, $annotation, $reflProperty)
                        );
                    }
                }
            }
        }

        //Method annotations
        foreach ($reflClass->getMethods() as $reflMethod) {

            foreach ($this->annotationReader->getMethodAnnotations($reflMethod) as $annotation) {
                if (defined(get_class($annotation) . '::event')) {

                    // Raise annotation event
                    if ($eventManager->hasListeners($annotation::event)) {
                        $eventManager->dispatchEvent(
                            $annotation::event,
                            new AnnotationEventArgs($metadata, EventType::method, $annotation, $reflMethod)
                        );
                    }
                }
            }
        }
    }
}
