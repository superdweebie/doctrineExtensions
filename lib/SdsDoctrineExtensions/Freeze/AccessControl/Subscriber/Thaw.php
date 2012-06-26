<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze\AccessControl\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\State\StateAwareInterface;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;
use SdsCommon\User\RoleAwareUserInterface;
use SdsDoctrineExtensions\AccessControl\AccessController;
use SdsDoctrineExtensions\Freeze\AccessControl\Event\Events as AccessControlEvents;
use SdsDoctrineExtensions\Freeze\AccessControl\Constant\Action;
use SdsDoctrineExtensions\Freeze\Event\Events as FreezeEvents;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Thaw implements EventSubscriber, ActiveUserAwareInterface
{
    use ActiveUserAwareTrait;

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
    public function preThaw(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if($document instanceof AccessControlledInterface &&
            $document instanceof StateAwareInterface &&
            !AccessController::isActionAllowed($document, Action::thaw, $this->activeUser)
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
