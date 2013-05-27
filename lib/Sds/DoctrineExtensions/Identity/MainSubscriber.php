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

        //Check update and raise events
        foreach ($unitOfWork->getScheduledDocumentUpdates() as $document) {

            $metadata = $documentManager->getClassMetadata(get_class($document));
            $changeSet = $unitOfWork->getDocumentChangeSet($document);

            $this->checkUpdateCredential($document, $metadata, $changeSet, $documentManager);
            $this->checkUpdateRoles($document, $metadata, $changeSet, $documentManager);
        }
    }

    protected function checkUpdateCredential($document, $metadata, $changeSet, $documentManager){

        // Check for credential changes
        if (! isset($metadata->credential) || ! isset($changeSet[$metadata->credential])){
            return;
        }

        $old = $changeSet[$metadata->credential][0];
        $new = $changeSet[$metadata->credential][1];

        $eventArgs = new UpdateCredentialEventArgs($old, $new, $document, $documentManager);

        $eventManager = $documentManager->getEventManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        // Raise preUpdateCredential
        if ($eventManager->hasListeners(Events::preUpdateCredential)) {
            $eventManager->dispatchEvent(Events::preUpdateCredential, $eventArgs);
        }

        if ( $metadata->reflFields[$metadata->credential]->getValue($document) == $old){
            //Roll back changes and continue
            $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
            return;
        }

        // Raise onUpdateCredential
        if ($eventManager->hasListeners(Events::onUpdateCredential)) {
            $eventManager->dispatchEvent(
                Events::onUpdateCredential,
                $eventArgs
            );
        }

        // Force change set update
        $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);

        // Raise postUpdateCredential
        if ($eventManager->hasListeners(Events::postUpdateCredential)) {
            $eventManager->dispatchEvent(
                Events::postUpdateCredential,
                $eventArgs
            );
        }
    }

    protected function checkUpdateRoles($document, $metadata, $changeSet, $documentManager){

        // Check for credential changes
        if (! isset($metadata->roles) || ! isset($changeSet[$metadata->roles])){
            return;
        }

        $old = $changeSet[$metadata->roles][0];
        $new = $changeSet[$metadata->roles][1];

        $eventArgs = new UpdateRolesEventArgs($old, $new, $document, $documentManager);

        $eventManager = $documentManager->getEventManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        // Raise preUpdateRoles
        if ($eventManager->hasListeners(Events::preUpdateRoles)) {
            $eventManager->dispatchEvent(Events::preUpdateRoles, $eventArgs);
        }

        if ( $metadata->reflFields[$metadata->roles]->getValue($document) == $old){
            //Roll back changes and continue
            $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
            return;
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