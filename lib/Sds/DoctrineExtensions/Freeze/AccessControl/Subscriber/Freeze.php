<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\AccessControl\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Sds\Common\AccessControl\AccessControlledInterface;
use Sds\Common\State\StateAwareInterface;
use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\ActiveUserAwareTrait;
use Sds\Common\User\RoleAwareUserInterface;
use Sds\DoctrineExtensions\AccessControl\AccessController;
use Sds\DoctrineExtensions\Freeze\AccessControl\Event\Events as AccessControlEvents;
use Sds\DoctrineExtensions\Freeze\AccessControl\Constant\Action;
use Sds\DoctrineExtensions\Freeze\Event\Events as FreezeEvents;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Freeze implements EventSubscriber, ActiveUserAwareInterface
{
    use ActiveUserAwareTrait;

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
    public function preFreeze(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if($document instanceof AccessControlledInterface &&
            !AccessController::isActionAllowed($document, Action::freeze, $this->activeUser)
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
