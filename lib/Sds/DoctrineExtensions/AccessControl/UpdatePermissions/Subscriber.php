<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\UpdatePermissions;

use Sds\Common\AccessControl\Constant\Action;
use Sds\DoctrineExtensions\AccessControl\AbstractAccessControlSubscriber;
use Sds\DoctrineExtensions\AccessControl\AccessController;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber extends AbstractAccessControlSubscriber
{
    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            Events::preUpdatePermissions
        );
    }

    public function preUpdatePermissions(EventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        $documentManager = $eventArgs->getDocumentManager();

        if ( AccessController::isAccessControlEnabled(
                $documentManager->getClassMetadata(get_class($document)),
                Action::updatePermissions,
                true
            ) &&
            ! AccessController::isActionAllowed($document, Action::updatePermissions, $this->roles)
        ) {

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(Events::updatePermissionsDenied)) {
                $eventManager->dispatchEvent(
                    Events::updatePermissionsDenied,
                    $eventArgs
                );
            }

            $eventArgs->setStopUpdatePermissions(true);
        }
    }
}
