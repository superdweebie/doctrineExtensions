<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use SdsDoctrineExtensions\SoftDelete\Event\Events as SoftDeleteEvents;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsDoctrineExtensions\SoftDelete\Mapping\MetadataInjector\SoftDelete as MetadataInjector;

/**
 * Emits soft delete events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SoftDelete implements EventSubscriber
{

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
        $eventManager = $documentManager->getEventManager();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {

            $metadata = $documentManager->getClassMetadata(get_class($document));
            if (!isset($metadata->softDelete)) {
                continue;
            }
            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $field = $metadata->softDelete['field'];
            if (!isset($changeSet[$field])) {
                continue;
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

                $getMethod = $metadata->softDelete['getMethod'];
                if($document->getMethod()){
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
                    $unitOfWork->computeSingleDocumentChangeSet($metadata, $document);
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

                $getMethod = $metadata->softDelete['getMethod'];
                if(!$document->getMethod()){
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
                    $unitOfWork->computeSingleDocumentChangeSet($metadata, $document);
                }
            }
        }
    }
}