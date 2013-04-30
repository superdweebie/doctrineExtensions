<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State;

use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 * Emits soft delete events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationSubscriber extends AbstractLazySubscriber
{

    /**
     *
     * @return array
     */
    public static function getStaticSubscribedEvents(){
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
        $eventArgs->getMetadata()->state = $eventArgs->getReflection()->getName();
    }
}