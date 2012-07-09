<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\AccessControl\Subscriber;

use Doctrine\Common\EventSubscriber;
use Sds\Common\AccessControl\AccessControlledInterface;
use Sds\Common\State\StateAwareInterface;
use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\ActiveUserAwareTrait;
use Sds\Common\User\RoleAwareUserInterface;
use Sds\DoctrineExtensions\AccessControl\AccessController;
use Sds\DoctrineExtensions\State\AccessControl\Event\Events as AccessControlEvents;
use Sds\DoctrineExtensions\State\Event\EventArgs as StateChangeEventArgs;
use Sds\DoctrineExtensions\State\Event\Events as StateEvents;
use Sds\DoctrineExtensions\State\Transition;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class StateChange implements EventSubscriber, ActiveUserAwareInterface
{
    use ActiveUserAwareTrait;

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            StateEvents::preStateChange
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
    public function preStateChange(StateChangeEventArgs $eventArgs)
    {
        $fromState = $eventArgs->getFromState();
        $toState = $eventArgs->getToState();
        $document = $eventArgs->getDocument();

        if($document instanceof AccessControlledInterface &&
            !AccessController::isActionAllowed(
                $document,
                Transition::getAction($fromState, $toState),
                $this->activeUser,
                $fromState
            )
        ) {
            //stop state change
            $document->setState($fromState);

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(AccessControlEvents::stateChangeDenied)) {
                $eventManager->dispatchEvent(
                    AccessControlEvents::stateChangeDenied,
                    $eventArgs
                );
            }
        }
    }
}
