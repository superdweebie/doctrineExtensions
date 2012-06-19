<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Readonly\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use SdsDoctrineExtensions\Readonly\Mapping\MetadataInjector\Readonly as MetadataInjector;
use SdsDoctrineExtensions\Common\AnnotationReaderAwareTrait;
use SdsDoctrineExtensions\Common\AnnotationReaderAwareInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Doctrine\Common\Annotations\Reader;

/**
 * Listener enforces readonly annotation
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Readonly implements EventSubscriber, AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;

    /**
     * 
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     */
    public function __construct(Reader $annotationReader){
        $this->setReader($annotationReader);
    }
    
    /**
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            ODMEvents::loadClassMetadata,
            ODMEvents::onFlush
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

    /**
     *
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {
            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $metadata = $documentManager->getClassMetadata(get_class($document));
            foreach ($changeSet as $field => $change){
                $old = $change[0];
                $new = $change[1];
                if(isset($metadata->fieldMappings[$field][MetadataInjector::READONLY]) &&
                    $metadata->fieldMappings[$field][MetadataInjector::READONLY]['value'] && 
                    $old != null
                ){
                    if($old != $new){
                        $setMethod = $metadata->fieldMappings[$field][MetadataInjector::READONLY]['setMethod'];
                        if (!method_exists($document, $setMethod)){
                            throw new \BadMethodCallException(sprintf(
                                'Method %s not found. This method was defined in the @readonly annotation
                                 to be used for resetting a property',
                                $setMethod
                            ));
                        }
                        $document->$setMethod($old);
                        $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                    }
                }
            }
        }
    }
}
