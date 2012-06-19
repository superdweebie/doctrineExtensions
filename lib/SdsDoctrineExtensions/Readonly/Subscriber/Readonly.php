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
use SdsDoctrineExtensions\Common\Behaviour\AnnotationReaderTrait;
use SdsDoctrineExtensions\Common\AnnotationReaderInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;

/**
 * Listener enforces readonly annotation
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Readonly implements EventSubscriber, AnnotationReaderInterface
{
    use AnnotationReaderTrait;

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
                if(isset($metadata->fieldMappings[$field][ReadonlyDriver::READONLY]) &&
                    $metadata->fieldMappings[$field][ReadonlyDriver::READONLY] && $old != null
                ){
                    if($old != $new){
                        $setMethod = 'set'.ucfirst($field);
                        if (!method_exists($document, $setMethod)){
                            throw new \ExceptionMethodNotFound(sprintf(
                                'Method %s not found. This method is required when using the @readonly annotation',
                                $setMethod
                            ));
                        }
                        $document->$setMethod($old);
                        $unitOfWork->recomputeSingleEntityChangeSet($metadata, $document);
                    }
                }
            }
        }
    }
}
