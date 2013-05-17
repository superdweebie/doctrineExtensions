<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Rest;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 * Adds dojo values to classmetadata
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
            Sds\Rest::event,
            Sds\Rest\Cache::event
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationRest(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadata = $eventArgs->getMetadata();

        if (!isset($metadata->rest)){
            $metadata->rest = [];
        }

        $metadata->rest = array_merge(
            $metadata->rest,
            [
                'endpoint' => isset($annotation->value) ? $annotation->value : strtolower($eventArgs->getMetadata()->collection)
            ]
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationRestCache(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadata = $eventArgs->getMetadata();

        if (!isset($metadata->rest)){
            $metadata->rest = [];
        }

        $cache = [];
        if ($annotation->public){
            $cache['public'] = true;
        }
        if ($annotation->private){
            $cache['private'] = true;
        }
        if ($annotation->noCache){
            $cache['no-cache'] = true;
        }
        if ($annotation->maxAge){
            $cache['max-age'] = true;
        }

        $metadata->rest = array_merge(
            $metadata->rest,
            [
                'cache' => $cache
            ]
        );
    }
}