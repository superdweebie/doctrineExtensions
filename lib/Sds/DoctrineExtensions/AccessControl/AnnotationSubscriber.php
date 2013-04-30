<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationSubscriber extends AbstractLazySubscriber {

    /**
     *
     * @return array
     */
    public static function getStaticSubscribedEvents(){
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