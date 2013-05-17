<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

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
        $eventArgs->getMetadata()->freeze['flag'] = $eventArgs->getReflection()->getName();
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