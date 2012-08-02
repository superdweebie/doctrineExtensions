<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\Common\SoftDelete\SoftDeleteableInterface;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Exception;
use Sds\DoctrineExtensions\SoftDelete\Events as SoftDeleteEvents;

/**
 * Emits soft delete events
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
            Sds\SoftDeleteField::event,
            ODMEvents::onFlush
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSoftDeleteField(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadataKey = $annotation::metadataKey;
        $eventArgs->getMetadata()->$metadataKey = $eventArgs->getReflection()->getName();
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
                throw new Exception\DocumentException(sprintf(
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