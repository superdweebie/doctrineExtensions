<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity;

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
            Sds\Roles::event
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationRoles(AnnotationEventArgs $eventArgs)
    {
        if ($eventArgs->getAnnotation()->value){
            $eventArgs->getMetadata()->roles = $eventArgs->getReflection()->getName();
        }
    }
}