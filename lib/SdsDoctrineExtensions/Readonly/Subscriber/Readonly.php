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
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use SdsDoctrineExtensions\Readonly\Event\Events as ReadonlyEvents;
use SdsDoctrineExtensions\Readonly\Event\EventArgs;
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

                // Raise preReadonlyRestore
                if ($eventManager->hasListeners(ReadonlyEvents::preReadonlyRestore)) {
                    $eventManager->dispatchEvent(
                        ReadonlyEvents::preReadonlyRestore,
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

                $setMethod = $metadata->fieldMappings[$field][MetadataInjector::readonly]['setMethod'];
                if (!method_exists($document, $setMethod)){
                    throw new \BadMethodCallException(sprintf(
                        'Method %s not found. This method was defined in the @readonly annotation
                            to be used for resetting a property',
                        $setMethod
                    ));
                }
                $document->$setMethod($old);
                $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);

                // Raise postReadonlyRestore
                if ($eventManager->hasListeners(ReadonlyEvents::postReadonlyRestore)) {
                    $eventManager->dispatchEvent(
                        ReadonlyEvents::postReadonlyRestore,
                        new EventArgs($field, $old, $new, $document, $documentManager)
                    );
                }
            }
        }
    }
}
