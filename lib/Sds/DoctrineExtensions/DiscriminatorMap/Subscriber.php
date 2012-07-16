<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DiscriminatorMap;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber, AnnotationReaderAwareInterface
{

    use AnnotationReaderAwareTrait;

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     */
    public function __construct(Reader $annotationReader){
        $this->setAnnotationReader($annotationReader);
    }

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
        Sds\DiscriminatorMap::event
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDiscriminatorMap(AnnotationEventArgs $eventArgs)
    {
        $className = $eventArgs->getAnnotation()->value;
        $mapClass = new $className;
        $eventArgs->getMetadata()->setDiscriminatorMap($mapClass->getDiscriminatorMap());
    }
}