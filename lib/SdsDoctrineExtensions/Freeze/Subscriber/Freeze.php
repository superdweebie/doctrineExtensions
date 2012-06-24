<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use SdsDoctrineExtensions\Freeze\Event\Events as FreezeEvents;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsDoctrineExtensions\Freeze\Mapping\MetadataInjector\Freeze as MetadataInjector;
use SdsCommon\Freeze\FreezeableInterface;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\Annotations\Reader;

/**
 * Emits soft delete events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Freeze implements EventSubscriber, AnnotationReaderAwareInterface
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