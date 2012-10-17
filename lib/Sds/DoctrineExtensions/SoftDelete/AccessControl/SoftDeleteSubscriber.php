<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\AccessControl;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Sds\DoctrineExtensions\AccessControl\AbstractAccessControlSubscriber;
use Sds\DoctrineExtensions\AccessControl\AccessController;
use Sds\DoctrineExtensions\SoftDelete\AccessControl\Events as AccessControlEvents;
use Sds\DoctrineExtensions\SoftDelete\AccessControl\Constant\Action;
use Sds\DoctrineExtensions\SoftDelete\Events as SoftDeleteEvents;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SoftDeleteSubscriber extends AbstractAccessControlSubscriber
{
    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            SoftDeleteEvents::preSoftDelete
        );
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function preSoftDelete(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        $documentManager = $eventArgs->getDocumentManager();

        if( AccessController::isAccessControlEnabled($documentManager->getClassMetadata(get_class($document)), Action::softDelete) &&
            !AccessController::isActionAllowed($document, Action::softDelete, $this->roles)
        ) {
            //stop SoftDelete
            $document->restore();

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(AccessControlEvents::softDeleteDenied)) {
                $eventManager->dispatchEvent(
                    AccessControlEvents::softDeleteDenied,
                    $eventArgs
                );
            }
        }
    }
}
