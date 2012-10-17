<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\AccessControl;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Sds\DoctrineExtensions\AccessControl\AbstractAccessControlSubscriber;
use Sds\DoctrineExtensions\AccessControl\AccessController;
use Sds\DoctrineExtensions\Freeze\AccessControl\Events as AccessControlEvents;
use Sds\DoctrineExtensions\Freeze\AccessControl\Constant\Action;
use Sds\DoctrineExtensions\Freeze\Events as FreezeEvents;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class FreezeSubscriber extends AbstractAccessControlSubscriber
{

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            FreezeEvents::preFreeze
        );
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function preFreeze(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        $documentManager = $eventArgs->getDocumentManager();

        if ( AccessController::isAccessControlEnabled($documentManager->getClassMetadata(get_class($document)), Action::freeze) &&
            !AccessController::isActionAllowed($document, Action::freeze, $this->roles)
        ) {
            //stop freeze
            $document->thaw();

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(AccessControlEvents::freezeDenied)) {
                $eventManager->dispatchEvent(
                    AccessControlEvents::freezeDenied,
                    $eventArgs
                );
            }
        }
    }
}
