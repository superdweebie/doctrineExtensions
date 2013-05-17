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
            Sds\AccessControl\IgnoreUpdate::event
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationAccessControlIgnoreUpdate(AnnotationEventArgs $eventArgs)
    {
        if ($eventArgs->getAnnotation()->value){
            $eventArgs->getMetadata()->accessControl['ignoreUpdate'][] = $eventArgs->getReflection()->getName();
        }
    }
}