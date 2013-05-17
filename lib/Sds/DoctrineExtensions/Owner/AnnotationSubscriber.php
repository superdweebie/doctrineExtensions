<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Owner;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

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
            Sds\Owner::event,
        ];
    }

    public function annotationOwner(AnnotationEventArgs $eventArgs){
        $metadata = $eventArgs->getMetadata();
        $metadata->owner = $eventArgs->getReflection()->getName();
    }
}