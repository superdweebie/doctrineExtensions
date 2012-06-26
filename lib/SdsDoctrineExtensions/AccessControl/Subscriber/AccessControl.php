<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl\Subscriber;

use Doctrine\Common\EventSubscriber;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\State\StateAwareInterface;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;
use SdsCommon\User\RoleAwareUserInterface;
use SdsDoctrineExtensions\AccessControl\AccessController;
use SdsDoctrineExtensions\AccessControl\Events as AccessControlEvents;
use SdsDoctrineExtensions\AccessControl\Constant\Action;



/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AccessControl implements EventSubscriber, ActiveUserAwareInterface
{
    use ActiveUserAwareTrait;

    /**
     *
     * @var boolean
     */
    protected $accessControlCreate = true;

    /**
     *
     * @var boolean
     */
    protected $accessControlUpdate = true;

    /**
     *
     * @var boolean
     */
    protected $accessControlDelete =true;

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            ODMEvents::onFlush
        );
    }

    /**
     *
     * @param \SdsCommon\AccessControl\RoleAwareUserInterface $activeUser
     */
    public function __construct(
        RoleAwareUserInterface $activeUser,
        $controlCreate = true,
        $controlUpdate = true,
        $controlDelete = true
    ) {
        $this->setActiveUser($activeUser);
        $this->controlCreate = $controlCreate;
        $this->controlUpdate = $controlUpdate;
        $this->controlDelete = $controlDelete;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();
        $eventManager = $documentManager->getEventManager();

        //Check create permissions
        if ($this->controlCreate){
            foreach ($unitOfWork->getScheduledDocumentInsertions() as $document) {
                if($document instanceof AccessControlledInterface &&
                    $document instanceof StateAwareInterface &&
                    !AccessController::isActionAllowed($document, Action::create, $this->activeUser)
                ) {
                    //stop creation
                    $unitOfWork->detach($document);

                    if ($eventManager->hasListeners(AccessControlEvents::createDenied)) {
                        $eventManager->dispatchEvent(
                            AccessControlEvents::createDenied,
                            new LifecycleEventArgs($document, $documentManager)
                        );
                    }
                }
            }
        }

        //Check update permissions
        if ($this->controlUpdate){
            foreach ($unitOfWork->getScheduledDocumentUpdates() as $document) {
                if($document instanceof AccessControlledInterface &&
                    $document instanceof StateAwareInterface &&
                    !AccessController::isActionAllowed($document, Action::update, $this->activeUser)
                ) {
                    //stop updates
                    $unitOfWork->clearDocumentChangeSet(spl_object_hash($document));

                    if ($eventManager->hasListeners(AccessControlEvents::updateDenied)) {
                        $eventManager->dispatchEvent(
                            AccessControlEvents::updateDenied,
                            new LifecycleEventArgs($document, $documentManager)
                        );
                    }
                }
            }
        }

        //Check delete permsisions
        if ($this->controlDelete){
            foreach ($unitOfWork->getScheduledDocumentDeletions() as $document) {
                if($document instanceof AccessControlledInterface &&
                    $document instanceof StateAwareInterface &&
                    !AccessController::isActionAllowed($document, Action::delete, $this->activeUser)
                ) {
                    //stop delete
                    $documentManager->persist($document);

                    if ($eventManager->hasListeners(AccessControlEvents::deleteDenied)) {
                        $eventManager->dispatchEvent(
                            AccessControlEvents::deleteDenied,
                            new LifecycleEventArgs($document, $documentManager)
                        );
                    }
                }
            }
        }
    }
}