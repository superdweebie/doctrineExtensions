<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Annotation\EventType;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationSubscriber implements EventSubscriber {

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return [
            Sds\AccessControl::event
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationAccessControl(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadata = $eventArgs->getMetadata();

        if ($annotation->value){
            $metadata->permissions = [];
            $eventManager = $eventArgs->getEventManager();
            foreach ($annotation->value as $permissionAnnotation){
                if (defined(get_class($permissionAnnotation) . '::event')) {

                    // Raise annotation event
                    if ($eventManager->hasListeners($permissionAnnotation::event)) {
                        $eventManager->dispatchEvent(
                            $permissionAnnotation::event,
                            new AnnotationEventArgs($metadata, EventType::document, $permissionAnnotation, $eventArgs->getReflection(), $eventManager)
                        );
                    }
                }
            }
        }
    }
}