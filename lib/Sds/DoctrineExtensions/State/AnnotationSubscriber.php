<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AccessControl\Actions;
use Sds\DoctrineExtensions\AccessControl\BasicPermission;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Annotation\EventType;

/**
 * Emits soft delete events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationSubscriber implements EventSubscriber
{

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            Sds\State::event,
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationState(AnnotationEventArgs $eventArgs)
    {
        $field = $eventArgs->getReflection()->getName();
        $metadata = $eventArgs->getMetadata();
        $eventManager = $eventArgs->getEventManager();

        $metadata->state = $field;

        //Add sythentic annotation to create extra permission that will allow
        //updates on the state field when access control is enabled.
        $permissionAnnotation = new Sds\Permission\Basic([
            'roles' => BasicPermission::wild,
            'allow' => Actions::update($field)
        ]);

        // Raise annotation event
        if ($eventManager->hasListeners($permissionAnnotation::event)) {
            $eventManager->dispatchEvent(
                $permissionAnnotation::event,
                new AnnotationEventArgs($metadata, EventType::document, $permissionAnnotation, $metadata->getReflectionClass(), $eventManager)
            );
        }
    }
}