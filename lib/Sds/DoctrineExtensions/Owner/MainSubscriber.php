<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Owner;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\DoctrineExtensions\Stamp\AbstractStampSubscriber;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MainSubscriber extends AbstractStampSubscriber {

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return [
            ODMEvents::prePersist,
            ODMEvents::onFlush
        ];
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs) {
        $document = $eventArgs->getDocument();
        $metadata = $eventArgs->getDocumentManager()->getClassMetadata(get_class($document));

        if(isset($metadata->owner)){
            $reflField = $metadata->reflFields[$metadata->owner];
            $owner = $reflField->getValue($document);
            if (!isset($owner)){
                $reflField->setValue($document, $this->getIdentityName());
            }
        }
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {

        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();
        $eventManager = $documentManager->getEventManager();

        //Check update permissions
        foreach ($unitOfWork->getScheduledDocumentUpdates() as $document) {

            $metadata = $documentManager->getClassMetadata(get_class($document));

            // Check for owner changes
            if ( !isset($metadata->owner)){
                continue;
            }

            $changeSet = $unitOfWork->getDocumentChangeSet($document);

            if (!isset($changeSet[$metadata->owner])){
                continue;
            }

            $old = $changeSet[$metadata->owner][0];
            $new = $changeSet[$metadata->owner][1];

            $eventArgs = new EventArgs($old, $new, $document, $documentManager);

            // Raise preUpdateOwner
            if ($eventManager->hasListeners(Events::preUpdateOwner)) {
                $eventManager->dispatchEvent(Events::preUpdateOwner, $eventArgs);
            }

            if ( $metadata->reflFields[$metadata->owner]->getValue($document) == $old){
                //Roll back changes and continue
                $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                continue;
            }

            // Raise onUpdateOwner
            if ($eventManager->hasListeners(Events::onUpdateOwner)) {
                $eventManager->dispatchEvent(
                    Events::onUpdateOwner,
                    $eventArgs
                );
            }

            // Force change set update
            $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);

            // Raise postUpdateOwner
            if ($eventManager->hasListeners(Events::postUpdateOwner)) {
                $eventManager->dispatchEvent(
                    Events::postUpdateOwner,
                    $eventArgs
                );
            }
        }
    }
}