<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MainSubscriber implements EventSubscriber {

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return [
            ODMEvents::onFlush
        ];
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

            // Check for roles changes
            if ( !isset($metadata->roles)){
                continue;
            }

            $changeSet = $unitOfWork->getDocumentChangeSet($document);

            if (!isset($changeSet[$metadata->roles])){
                continue;
            }

            $old = $changeSet[$metadata->roles][0];
            $new = $changeSet[$metadata->roles][1];

            $eventArgs = new EventArgs($old, $new, $document, $documentManager);

            // Raise preUpdateRoles
            if ($eventManager->hasListeners(Events::preUpdateRoles)) {
                $eventManager->dispatchEvent(Events::preUpdateRoles, $eventArgs);
            }

            if ( $metadata->reflFields[$metadata->roles]->getValue($document) == $old){
                //Roll back changes and continue
                $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                continue;
            }

            // Raise onUpdateRoles
            if ($eventManager->hasListeners(Events::onUpdateRoles)) {
                $eventManager->dispatchEvent(
                    Events::onUpdateRoles,
                    $eventArgs
                );
            }

            // Force change set update
            $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);

            // Raise postUpdateRoles
            if ($eventManager->hasListeners(Events::postUpdateRoles)) {
                $eventManager->dispatchEvent(
                    Events::postUpdateRoles,
                    $eventArgs
                );
            }
        }
    }
}