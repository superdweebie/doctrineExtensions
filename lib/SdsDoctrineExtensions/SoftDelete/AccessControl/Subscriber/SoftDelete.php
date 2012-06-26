<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\AccessControl\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\State\StateAwareInterface;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;
use SdsCommon\User\RoleAwareUserInterface;
use SdsDoctrineExtensions\AccessControl\AccessController;
use SdsDoctrineExtensions\SoftDelete\AccessControl\Event\Events as AccessControlEvents;
use SdsDoctrineExtensions\SoftDelete\AccessControl\Constant\Action;
use SdsDoctrineExtensions\SoftDelete\Event\Events as SoftDeleteEvents;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SoftDelete implements EventSubscriber, ActiveUserAwareInterface
{
    use ActiveUserAwareTrait;

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            SoftDeleteEvents::preSoftDelete
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
    public function preSoftDelete(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if($document instanceof AccessControlledInterface &&
            $document instanceof StateAwareInterface &&
            !AccessController::isActionAllowed($document, Action::softDelete, $this->activeUser)
        ) {
            //stop SoftDelete
            $document->restore();

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(AccessControlEvents::softDeleteDenied)) {
                $eventManager->dispatchEvent(
                    AccessControlEvents::softDeleteDenied,
                    $eventArgs
                );
            }
        }
    }
}
