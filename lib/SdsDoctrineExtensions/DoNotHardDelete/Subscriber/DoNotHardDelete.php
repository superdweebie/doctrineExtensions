<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\DoNotHardDelete\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use SdsDoctrineExtensions\DoNotHardDelete\Event\Events as DoNotHardDeleteEvents;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsDoctrineExtensions\DoNotHardDelete\Mapping\MetadataInjector\DoNotHardDelete as MetadataInjector;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use Doctrine\Common\Annotations\Reader;

/**
 * Enforces doNotHardDelete
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DoNotHardDelete implements EventSubscriber, AnnotationReaderAwareInterface
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
     * @param \Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs $eventArgs
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

        foreach ($unitOfWork->getScheduledDocumentDeletions() AS $document) {
            $metadata = $documentManager->getClassMetadata(get_class($document));

            if (!isset($metadata->doNotHardDelete)) {
                continue;
            }

            // Persist to undo deletion
            $documentManager->persist($document);

            // Raise hardDeleteRefused event
            if ($eventManager->hasListeners(DoNotHardDeleteEvents::hardDeleteRefused)) {
                $eventManager->dispatchEvent(
                    DoNotHardDeleteEvents::hardDeleteRefused,
                    new LifecycleEventArgs($document, $documentManager)
                );
            }
        }
    }
}