<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity\AccessControl;

use Sds\DoctrineExtensions\Identity\Actions;
use Sds\DoctrineExtensions\Identity\Events;
use Sds\DoctrineExtensions\Identity\EventArgs;
use Sds\DoctrineExtensions\AccessControl\AbstractAccessControlSubscriber;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UpdateRolesSubscriber extends AbstractAccessControlSubscriber
{
    
    /**
     *
     * @return array
     */
    public static function getStaticSubscribedEvents(){
        return [
            Events::preUpdateRoles
        ];
    }

    public function preUpdateRoles(EventArgs $eventArgs)
    {
        if (! ($accessController = $this->getAccessController())){
            //Access control is not enabled
            return;
        }

        $document = $eventArgs->getDocument();

        if ( ! $accessController->isAllowed(Actions::updateRoles, null, $document)->getIsAllowed()) {

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
