<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Readonly;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Sds\DoctrineExtensions\Accessor\MetadataInjector as AccessorInjector;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Readonly\Events as ReadonlyEvents;
use Sds\DoctrineExtensions\Readonly\EventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Doctrine\Common\Annotations\Reader;

/**
 * Listener enforces readonly annotation
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
     * @throws \BadMethodCallException
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();
        $eventManager = $documentManager->getEventManager();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {
            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $metadata = $documentManager->getClassMetadata(get_class($document));
            foreach ($changeSet as $field => $change){
                $old = $change[0];
                $new = $change[1];

                // Check for change and readonly annotation
                if(!isset($metadata->fieldMappings[$field][MetadataInjector::readonly]) ||
                    $old == null ||
                    $old == $new
                ){
                    continue;
                }

                // Raise preReadonlyRollback
                if ($eventManager->hasListeners(ReadonlyEvents::preReadonlyRollback)) {
                    $eventManager->dispatchEvent(
                        ReadonlyEvents::preReadonlyRollback,
                        new EventArgs($field, $old, $new, $document, $documentManager)
                    );
                    $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                    $changeSet = $unitOfWork->getDocumentChangeSet($document);
                    $new = $changeSet[$field][1];

                    // Continue if value has been changed back to old.
                    if($old == $new) {
                        continue;
                    }
                }

                if(isset($metadata->fieldMappings[$field][AccessorInjector::setter])
                ){
                    $setMethod = $metadata->fieldMappings[$field][AccessorInjector::setter];
                } else {
                    $setMethod = 'set'.ucfirst($field);
                }
            
                if (!method_exists($document, $setMethod)){
                    throw new \BadMethodCallException(sprintf(
                        'Method %s not found. This method was defined in the @readonly annotation
                            to be used for resetting a property',
                        $setMethod
                    ));
                }
                $document->$setMethod($old);
                $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);

                // Raise postReadonlyRollback
                if ($eventManager->hasListeners(ReadonlyEvents::postReadonlyRollback)) {
                    $eventManager->dispatchEvent(
                        ReadonlyEvents::postReadonlyRollback,
                        new EventArgs($field, $old, $new, $document, $documentManager)
                    );
                }
            }
        }
    }
}
