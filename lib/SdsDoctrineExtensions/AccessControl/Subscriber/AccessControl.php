<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl\Subscriber;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\State\StateAwareInterface;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;
use SdsCommon\User\RoleAwareUserInterface;
use SdsDoctrineExtensions\AccessControl\AccessController;
use SdsDoctrineExtensions\AccessControl\Constant\Action;
use SdsDoctrineExtensions\AccessControl\Event\Events as AccessControlEvents;
use SdsDoctrineExtensions\AccessControl\Mapping\MetadataInjector\AccessControl as MetadataInjector;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use SdsDoctrineExtensions\State\Event\EventArgs as StateEventArgs;
use SdsDoctrineExtensions\State\Event\Events as StateEvents;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AccessControl implements
    EventSubscriber,
    AnnotationReaderAwareInterface,
    ActiveUserAwareInterface
{
    use ActiveUserAwareTrait;
    use AnnotationReaderAwareTrait;

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
            ODMEvents::loadClassMetadata,
            ODMEvents::onFlush,
            StateEvents::onStateChange
        );
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param \SdsCommon\User\RoleAwareUserInterface $activeUser
     * @param boolean $controlCreate
     * @param boolean $controlUpdate
     * @param boolean $controlDelete
     */
    public function __construct(
        Reader $annotationReader,
        RoleAwareUserInterface $activeUser,
        $controlCreate = true,
        $controlUpdate = true,
        $controlDelete = true
    ) {
        $this->setAnnotationReader($annotationReader);
        $this->setActiveUser($activeUser);
        $this->controlCreate = $controlCreate;
        $this->controlUpdate = $controlUpdate;
        $this->controlDelete = $controlDelete;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $metadataInjector = new MetadataInjector($this->annotationReader);
        $metadataInjector->loadMetadataForClass($metadata);
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

        foreach ($unitOfWork->getScheduledDocumentInsertions() as $document) {
            if($document instanceof AccessControlledInterface) {

                //Set stateEqualToParent on permissions
                if ($document instanceof StateAwareInterface) {
                    $documentState = $document->getState();
                    foreach ($document->getPermissions() as $permission){
                        $permission->setStateEqualToParent(($permission->getState() == $documentState));
                    }
                }

                //Check create permissions
                if ($this->controlCreate &&
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

                // Skip any updates on fields marked with @DoNotAccessControlUpdate
                $changeSet = $unitOfWork->getDocumentChangeSet($document);
                $metadata = $documentManager->getClassMetadata(get_class($document));
                $checkPermission = false;
                foreach ($changeSet as $field => $change) {
                    if (!isset($metadata->fieldMappings[$field][MetadataInjector::doNotAccessControlUpdate])) {
                        $checkPermission = true;
                        break;
                    } elseif (!$metadata->fieldMappings[$field][MetadataInjector::doNotAccessControlUpdate]) {
                        $checkPermission = true;
                        break;
                    }
                }
                if (!$checkPermission){
                    continue;
                }

                // allow updates to @stateField. If you need to control state updates, enable
                // access control in the state extension
                if ($document instanceof StateAwareInterface) {

                    $changeSet = $unitOfWork->getDocumentChangeSet($document);
                    $metadata = $documentManager->getClassMetadata(get_class($document));
                    $field = $metadata->stateField;

                    if (count($changeSet) == 1 && isset($changeSet[$field])) {
                        continue;
                    }
                }

                if ($document instanceof AccessControlledInterface &&
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

    /**
     *
     * @param \SdsDoctrineExtensions\State\Event\EventArgs $eventArgs
     */
    public function onStateChange(StateEventArgs $eventArgs){
        $document = $eventArgs->getDocument();

        if($document instanceof AccessControlledInterface) {
            $toState = $eventArgs->getToState();
            foreach ($document->getPermissions() as $permission){
                $permission->setStateEqualToParent(($permission->getState() == $toState));
            }
        }
    }
}