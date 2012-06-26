<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\State\AccessControl\Subscriber;

use Doctrine\Common\EventSubscriber;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\State\StateAwareInterface;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;
use SdsCommon\User\RoleAwareUserInterface;
use SdsDoctrineExtensions\AccessControl\AccessController;
use SdsDoctrineExtensions\State\AccessControl\Event\Events as AccessControlEvents;
use SdsDoctrineExtensions\State\Event\EventArgs as StateChangeEventArgs;
use SdsDoctrineExtensions\State\Event\Events as StateEvents;

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
     * @param \SdsCommon\AccessControl\RoleAwareUserInterface $activeUser
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
            $document instanceof StateAwareInterface &&
            !AccessController::isActionAllowed($document, Transition::getAction($fromState, $toState), $this->activeUser)
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
