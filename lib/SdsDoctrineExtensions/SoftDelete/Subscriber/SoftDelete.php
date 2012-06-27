<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\Subscriber;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsCommon\SoftDelete\SoftDeleteableInterface;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use SdsDoctrineExtensions\SoftDelete\Event\Events as SoftDeleteEvents;
use SdsDoctrineExtensions\SoftDelete\Mapping\MetadataInjector\SoftDelete as MetadataInjector;

/**
 * Emits soft delete events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SoftDelete implements EventSubscriber, AnnotationReaderAwareInterface
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
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {

            if (!$document instanceof SoftDeleteableInterface) {
                continue;
            }

            $metadata = $documentManager->getClassMetadata(get_class($document));
            if (!isset($metadata->softDeleteField)) {
                throw new \Exception(sprintf(
                    'Document class %s implements the SoftDeleteableInterface, but does not have a field annotatated as @softDeleteField.',
                    get_class($document)
                ));
            }

            $eventManager = $documentManager->getEventManager();
            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $field = $metadata->softDeleteField;

            if (!isset($changeSet[$field])) {
                if ($document->getSoftDeleted()) {
                    // Updates to softDeleted documents are not allowed. Roll them back
                    $unitOfWork->clearDocumentChangeSet(spl_object_hash($document));

                    // Raise softDeletedUpdateDenied
                    if ($eventManager->hasListeners(SoftDeleteEvents::softDeletedUpdateDenied)) {
                        $eventManager->dispatchEvent(
                            SoftDeleteEvents::softDeletedUpdateDenied,
                            new LifecycleEventArgs($document, $documentManager)
                        );
                    }
                    continue;
                } else {
                    continue;
                }
            }

            if ($changeSet[$field][1]) {
                // Trigger soft delete events

                // Raise preSoftDelete
                if ($eventManager->hasListeners(SoftDeleteEvents::preSoftDelete)) {
                    $eventManager->dispatchEvent(
                        SoftDeleteEvents::preSoftDelete,
                        new LifecycleEventArgs($document, $documentManager)
                    );
                }

                if($document->getSoftDeleted()){
                    // Raise postSoftDelete
                    if ($eventManager->hasListeners(SoftDeleteEvents::postSoftDelete)) {
                        $eventManager->dispatchEvent(
                            SoftDeleteEvents::postSoftDelete,
                            new LifecycleEventArgs($document, $documentManager)
                        );
                    }
                } else {
                    // Soft delete has been rolled back
                    $metadata = $documentManager->getClassMetadata(get_class($document));
                    $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                }

            } else {
                // Trigger restore events

                // Raise preRestore
                if ($eventManager->hasListeners(SoftDeleteEvents::preRestore)) {
                    $eventManager->dispatchEvent(
                        SoftDeleteEvents::preRestore,
                        new LifecycleEventArgs($document, $documentManager)
                    );
                }

                if(!$document->getSoftDeleted()){
                    // Raise postRestore
                    if ($eventManager->hasListeners(SoftDeleteEvents::postRestore)) {
                        $eventManager->dispatchEvent(
                            SoftDeleteEvents::postRestore,
                            new LifecycleEventArgs($document, $documentManager)
                        );
                    }
                } else {
                    // Restore has been rolled back
                    $metadata = $documentManager->getClassMetadata(get_class($document));
                    $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                }
            }
        }
    }
}