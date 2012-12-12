<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Rest;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 * Adds dojo values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber, AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;

    protected $basePath;

    public function getBasePath() {
        return $this->basePath;
    }

    public function setBasePath($basePath) {
        $this->basePath = (string) $basePath;
    }

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            ODMEvents::loadClassMetadata,
            Sds\RestUrl::event
        );
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     */
    public function __construct(Reader $annotationReader, $basePath = null){
        $this->setAnnotationReader($annotationReader);
        $this->setBasePath($basePath);
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();

        if (isset($metadata->rest)){
            return;
        }

        $metadata->rest = ['url' => $this->getBasePath() . $metadata->collection];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationRestUrl(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        $eventArgs->getMetadata()->rest = [
            'url' => $this->getBasePath() . $annotation->value
        ];
    }
}