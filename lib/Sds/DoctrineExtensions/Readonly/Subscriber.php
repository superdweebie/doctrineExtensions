<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Readonly;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Exception;
use Sds\DoctrineExtensions\Readonly\Events as ReadonlyEvents;
use Sds\DoctrineExtensions\Readonly\EventArgs;

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
            Sds\Readonly::event,
            ODMEvents::onFlush
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationReadonly(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $eventArgs->getMetadata()->fieldMappings[$eventArgs->getReflection()->getName()][$annotation::metadataKey] = true;
    }

    /**
     *
     * @param OnFlushEventArgs $eventArgs
     * @throws Sds\DoctrineExtensions\Exception\BadMethodCallException
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
                if(!isset($metadata->fieldMappings[$field][Sds\Readonly::metadataKey]) ||
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

                if(isset($metadata->fieldMappings[$field][Sds\Setter::metadataKey])
                ){
                    $setMethod = $metadata->fieldMappings[$field][Sds\Setter::metadataKey];
                } else {
                    $setMethod = 'set'.ucfirst($field);
                }

                if (!method_exists($document, $setMethod)){
                    throw new Exception\BadMethodCallException(sprintf(
                        'Method %s not found. This method was defined in the @Setter annotation to be used for resetting a property',
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
