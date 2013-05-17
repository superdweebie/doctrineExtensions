<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

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
        return [
            Sds\SoftDelete::event,
            Sds\SoftDelete\DeletedBy::event,
            Sds\SoftDelete\DeletedOn::event,
            Sds\SoftDelete\RestoredBy::event,
            Sds\SoftDelete\RestoredOn::event,
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSoftDelete(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->softDelete['flag'] = $eventArgs->getReflection()->getName();
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSoftDeleteDeletedBy(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->softDelete['deletedBy'] = $eventArgs->getReflection()->getName();
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSoftDeleteDeletedOn(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->softDelete['deletedOn'] = $eventArgs->getReflection()->getName();
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSoftDeleteRestoredBy(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->softDelete['restoredBy'] = $eventArgs->getReflection()->getName();
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSoftDeleteRestoredOn(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->softDelete['restoredOn'] = $eventArgs->getReflection()->getName();
    }
}