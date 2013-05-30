<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AccessControl\Actions;
use Sds\DoctrineExtensions\AccessControl\BasicPermission;
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
    public function getSubscribedEvents() {
        return [
            Sds\Stamp\CreatedBy::event,
            Sds\Stamp\CreatedOn::event,
            Sds\Stamp\UpdatedOn::event,
            Sds\Stamp\UpdatedBy::event,
        ];
    }

    public function annotationStampCreatedBy(AnnotationEventArgs $eventArgs){

        $field = $eventArgs->getReflection()->getName();
        $metadata = $eventArgs->getMetadata();
        $eventManager = $eventArgs->getEventManager();

        $metadata->stamp['createdBy'] = $field;

        //Add sythentic annotation to create extra permission that will prevent
        //updates on the createdby field when access control is enabled.
        $permissionAnnotation = new Sds\Permission\Basic([
            'roles' => BasicPermission::wild,
            'deny' => Actions::update($field)
        ]);

        // Raise annotation event
        if ($eventManager->hasListeners($permissionAnnotation::event)) {
            $eventManager->dispatchEvent(
                $permissionAnnotation::event,
                new AnnotationEventArgs($metadata, EventType::document, $permissionAnnotation, $metadata->getReflectionClass(), $eventManager)
            );
        }
    }

    public function annotationStampCreatedOn(AnnotationEventArgs $eventArgs){

        $field = $eventArgs->getReflection()->getName();
        $metadata = $eventArgs->getMetadata();
        $eventManager = $eventArgs->getEventManager();

        $metadata->stamp['createdOn'] = $field;

        //Add sythentic annotation to create extra permission that will prevent
        //updates on the createdby field when access control is enabled.
        $permissionAnnotation = new Sds\Permission\Basic([
            'roles' => BasicPermission::wild,
            'deny' => Actions::update($field)
        ]);

        // Raise annotation event
        if ($eventManager->hasListeners($permissionAnnotation::event)) {
            $eventManager->dispatchEvent(
                $permissionAnnotation::event,
                new AnnotationEventArgs($metadata, EventType::document, $permissionAnnotation, $metadata->getReflectionClass(), $eventManager)
            );
        }
    }

    public function annotationStampUpdatedBy(AnnotationEventArgs $eventArgs){
        $metadata = $eventArgs->getMetadata();
        if (!isset($metadata->stamp)){
            $metadata->stamp = [];
        }
        $metadata->stamp['updatedBy'] = $eventArgs->getReflection()->getName();
    }

    public function annotationStampUpdatedOn(AnnotationEventArgs $eventArgs){
        $metadata = $eventArgs->getMetadata();
        if (!isset($metadata->stamp)){
            $metadata->stamp = [];
        }
        $metadata->stamp['updatedOn'] = $eventArgs->getReflection()->getName();
    }
}