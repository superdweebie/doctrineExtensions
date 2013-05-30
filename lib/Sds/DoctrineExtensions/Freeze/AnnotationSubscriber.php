<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AccessControl\Actions;
use Sds\DoctrineExtensions\AccessControl\BasicPermission;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Annotation\EventType;

/**
 * Emits freeze events
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
        return [
            Sds\Freeze::event,
            Sds\Freeze\FrozenBy::event,
            Sds\Freeze\FrozenOn::event,
            Sds\Freeze\ThawedBy::event,
            Sds\Freeze\ThawedOn::event,
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationFreeze(AnnotationEventArgs $eventArgs)
    {
        $field = $eventArgs->getReflection()->getName();
        $metadata = $eventArgs->getMetadata();
        $eventManager = $eventArgs->getEventManager();

        $metadata->freeze['flag'] = $field;

        //Add sythentic annotation to create extra permission that will allow
        //updates on the freeze field when access control is enabled.
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

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationFreezeFrozenBy(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->freeze['frozenBy'] = $eventArgs->getReflection()->getName();
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationFreezeFrozenOn(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->freeze['frozenOn'] = $eventArgs->getReflection()->getName();
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationFreezeThawedBy(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->freeze['thawedBy'] = $eventArgs->getReflection()->getName();
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationFreezeThawedOn(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->freeze['thawedOn'] = $eventArgs->getReflection()->getName();
    }
}