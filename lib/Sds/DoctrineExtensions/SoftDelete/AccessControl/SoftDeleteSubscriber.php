<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\AccessControl;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Sds\DoctrineExtensions\AccessControl\AbstractAccessControlSubscriber;
use Sds\DoctrineExtensions\SoftDelete\Events;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SoftDeleteSubscriber extends AbstractAccessControlSubscriber
{

    protected $softDeleter;

    /**
     *
     * @return array
     */
    public static function getStaticSubscribedEvents(){
        return [
            Events::preSoftDelete,
            Events::preRestore
        ];
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function preSoftDelete(LifecycleEventArgs $eventArgs)
    {
        if (! ($accessController = $this->getAccessController())){
            //Access control is not enabled
            return;
        }

        $document = $eventArgs->getDocument();

        if( !$accessController->isAllowed(Actions::softDelete, null, $document)->getIsAllowed()) {
            //stop SoftDelete
            $this->getSoftDeleter()->restore($document);

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(Events::softDeleteDenied)) {
                $eventManager->dispatchEvent(
                    Events::softDeleteDenied,
                    $eventArgs
                );
            }
        }
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function preRestore(LifecycleEventArgs $eventArgs)
    {
        if (! ($accessController = $this->getAccessController())){
            //Access control is not enabled
            return;
        }

        $document = $eventArgs->getDocument();

        if ( !$accessController->isAllowed(Actions::restore, null, $document)->getIsAllowed()) {
            //stop restore
            $this->getSoftDeleter()->softDelete($document);

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(Events::restoreDenied)) {
                $eventManager->dispatchEvent(
                    Events::restoreDenied,
                    $eventArgs
                );
            }
        }
    }

    protected function getSoftDeleter(){
        if (!isset($this->softDeleter)){
            $this->softDeleter = $this->serviceLocator->get('softDeleter');
        }
        return $this->softDeleter;
    }
}
