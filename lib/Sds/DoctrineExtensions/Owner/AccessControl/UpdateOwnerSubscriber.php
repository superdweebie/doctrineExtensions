<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Owner\AccessControl;

use Sds\DoctrineExtensions\AccessControl\AbstractAccessControlSubscriber;
use Sds\DoctrineExtensions\AccessControl\EventArgs as AccessControlEventArgs;
use Sds\DoctrineExtensions\Owner\Events;
use Sds\DoctrineExtensions\Owner\EventArgs;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UpdateOwnerSubscriber extends AbstractAccessControlSubscriber
{

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return [
            Events::preUpdateOwner,
        ];
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function preUpdateOwner(EventArgs $eventArgs)
    {
        if (! ($accessController = $this->getAccessController())){
            //Access control is not enabled
            return;
        }

        $document = $eventArgs->getDocument();

        if ( ! $accessController->isAllowed(Actions::updateOwner, null, $document)->getIsAllowed()) {
            //stop update
            $metadata = $eventArgs->getDocumentManager()->getClassMetadata(get_class($document));
            $metadata->reflFields[$metadata->owner]->setValue($document, $eventArgs->getOldOwner());

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(Events::updateOwnerDenied)) {
                $eventManager->dispatchEvent(
                    Events::updateOwnerDenied,
                    new AccessControlEventArgs($document, $eventArgs->getDocumentManager(), Actions::updateOwner)
                );
            }
        }
    }
}
