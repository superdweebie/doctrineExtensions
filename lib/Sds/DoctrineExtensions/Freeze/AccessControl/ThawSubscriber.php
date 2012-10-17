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
class ThawSubscriber extends AbstractAccessControlSubscriber
{

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            FreezeEvents::preThaw
        );
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function preThaw(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        $documentManager = $eventArgs->getDocumentManager();

        if ( AccessController::isAccessControlEnabled($documentManager->getClassMetadata(get_class($document)), Action::thaw) &&
            !AccessController::isActionAllowed($document, Action::thaw, $this->roles)
        ) {
            //stop freeze
            $document->freeze();

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(AccessControlEvents::thawDenied)) {
                $eventManager->dispatchEvent(
                    AccessControlEvents::thawDenied,
                    $eventArgs
                );
            }
        }
    }
}
