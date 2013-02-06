<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\UpdateRoles;

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
            Events::preUpdateRoles
        );
    }

    public function preUpdateRoles(EventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        $documentManager = $eventArgs->getDocumentManager();

        if ( AccessController::isAccessControlEnabled(
                $documentManager->getClassMetadata(get_class($document)),
                Action::updateRoles,
                true
            ) &&
            ! AccessController::isActionAllowed($document, Action::updateRoles, $this->roles)
        ) {

            $document->setRoles($eventArgs->getOldRoles());

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(Events::updateRolesDenied)) {
                $eventManager->dispatchEvent(
                    Events::updateRolesDenied,
                    $eventArgs
                );
            }
        }
    }
}
