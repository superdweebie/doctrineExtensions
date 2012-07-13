<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\Common\Freeze\FreezeableInterface;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Freeze\Events as FreezeEvents;

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
            Sds\FreezeField::event,
            ODMEvents::onFlush
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationFreezeField(AnnotationEventArgs $eventArgs)
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
        $eventManager = $documentManager->getEventManager();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {

            if (!$document instanceof FreezeableInterface) {
                continue;
            }

            $metadata = $documentManager->getClassMetadata(get_class($document));
            if (!isset($metadata->freezeField)) {
                throw new \Exception(sprintf(
                    'Document class %s implements the FreezeableInterface, but does not have a field annotatated as @freezeField.',
                    get_class($document)
                ));
            }

            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $field = $metadata->freezeField;

            if (!isset($changeSet[$field])) {
                if ($document->getFrozen()) {
                    // Updates to frozen documents are not allowed. Roll them back
                    $unitOfWork->clearDocumentChangeSet(spl_object_hash($document));

                    // Raise frozenUpdateDenied
                    if ($eventManager->hasListeners(FreezeEvents::frozenUpdateDenied)) {
                        $eventManager->dispatchEvent(
                            FreezeEvents::frozenUpdateDenied,
                            new LifecycleEventArgs($document, $documentManager)
                        );
                    }
                    continue;
                } else {
                    continue;
                }
            }

            if ($changeSet[$field][1]) {
                // Trigger freeze events

                // Raise preFreeze
                if ($eventManager->hasListeners(FreezeEvents::preFreeze)) {
                    $eventManager->dispatchEvent(
                        FreezeEvents::preFreeze,
                        new LifecycleEventArgs($document, $documentManager)
                    );
                }

                if($document->getFrozen()){
                    // Raise postFreeze
                    if ($eventManager->hasListeners(FreezeEvents::postFreeze)) {
                        $eventManager->dispatchEvent(
                            FreezeEvents::postFreeze,
                            new LifecycleEventArgs($document, $documentManager)
                        );
                    }
                } else {
                    // Freeze has been rolled back
                    $metadata = $documentManager->getClassMetadata(get_class($document));
                    $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                }

            } else {
                // Trigger thaw events

                // Raise preThaw
                if ($eventManager->hasListeners(FreezeEvents::preThaw)) {
                    $eventManager->dispatchEvent(
                        FreezeEvents::preThaw,
                        new LifecycleEventArgs($document, $documentManager)
                    );
                }

                if(!$document->getFrozen()){
                    // Raise postThaw
                    if ($eventManager->hasListeners(FreezeEvents::postThaw)) {
                        $eventManager->dispatchEvent(
                            FreezeEvents::postThaw,
                            new LifecycleEventArgs($document, $documentManager)
                        );
                    }
                } else {
                    // Thaw has been rolled back
                    $metadata = $documentManager->getClassMetadata(get_class($document));
                    $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                }
            }
        }

        foreach ($unitOfWork->getScheduledDocumentDeletions() AS $document) {

            if (!$document instanceof FreezeableInterface) {
                continue;
            }

            if (!$document->getFrozen()){
                continue;
            }

            // Deletions of frozen documents are not allowed. Roll them back
            $documentManager->persist($document);

            // Raise frozenDeleteDenied
            if ($eventManager->hasListeners(FreezeEvents::frozenDeleteDenied)) {
                $eventManager->dispatchEvent(
                    FreezeEvents::frozenDeleteDenied,
                    new LifecycleEventArgs($document, $documentManager)
                );
            }
        }
    }
}