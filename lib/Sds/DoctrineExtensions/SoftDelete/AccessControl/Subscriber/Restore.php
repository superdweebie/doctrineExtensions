<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\AccessControl\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Sds\Common\AccessControl\AccessControlledInterface;
use Sds\Common\State\StateAwareInterface;
use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\ActiveUserAwareTrait;
use Sds\Common\User\RoleAwareUserInterface;
use Sds\DoctrineExtensions\AccessControl\AccessController;
use Sds\DoctrineExtensions\SoftDelete\AccessControl\Event\Events as AccessControlEvents;
use Sds\DoctrineExtensions\SoftDelete\AccessControl\Constant\Action;
use Sds\DoctrineExtensions\SoftDelete\Event\Events as SoftDeleteEvents;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Restore implements EventSubscriber, ActiveUserAwareInterface
{
    use ActiveUserAwareTrait;

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            SoftDeleteEvents::preRestore
        );
    }

    /**
     *
     * @param \Sds\Common\AccessControl\RoleAwareUserInterface $activeUser
     */
    public function __construct(
        RoleAwareUserInterface $activeUser
    ) {
        $this->setRequireRoleAwareUser(true);
        $this->setActiveUser($activeUser);
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function preRestore(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if($document instanceof AccessControlledInterface &&
            !AccessController::isActionAllowed($document, Action::restore, $this->activeUser)
        ) {
            //stop restore
            $document->softDelete();

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(AccessControlEvents::restoreDenied)) {
                $eventManager->dispatchEvent(
                    AccessControlEvents::restoreDenied,
                    $eventArgs
                );
            }
        }
    }
}
