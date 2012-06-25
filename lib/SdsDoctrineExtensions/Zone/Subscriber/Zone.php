<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Zone\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsDoctrineExtensions\Zone\Mapping\MetadataInjector\Zone as MetadataInjector;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\Annotations\Reader;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Zone implements EventSubscriber, AnnotationReaderAwareInterface
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
            ODMEvents::loadClassMetadata
        );
    }

    /**
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $metadataInjector = new MetadataInjector($this->annotationReader);
        $metadataInjector->loadMetadataForClass($metadata);
    }
}