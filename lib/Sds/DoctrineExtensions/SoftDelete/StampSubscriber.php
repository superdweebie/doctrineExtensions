<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Sds\Common\SoftDelete\SoftDeletedByInterface;
use Sds\Common\SoftDelete\SoftDeletedOnInterface;
use Sds\Common\SoftDelete\RestoredByInterface;
use Sds\Common\SoftDelete\RestoredOnInterface;
use Sds\DoctrineExtensions\SoftDelete\Events as SoftDeleteEvents;
use Sds\DoctrineExtensions\Stamp\AbstractStampSubscriber;

/**
 * Adds create and update stamps during persist
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class StampSubscriber extends AbstractStampSubscriber {

    /**
     *
     * @return array
     */
    public function getSubscribedEvents() {
        return array(
            SoftDeleteEvents::postSoftDelete,
            SoftDeleteEvents::postRestore
        );
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs
     */
    public function postSoftDelete(LifecycleEventArgs $eventArgs) {
        $recomputeChangeSet = false;
        $document = $eventArgs->getDocument();
        if($document instanceof SoftDeletedByInterface){
            $document->setSoftDeletedBy($this->identityName);
            $recomputeChangeSet = true;
        }
        if($document instanceof SoftDeletedOnInterface){
            $document->setSoftDeletedOn(time());
            $recomputeChangeSet = true;
        }
        if ($recomputeChangeSet) {
            $this->recomputeChangeset($eventArgs);
        }
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs
     */
    public function postRestore(LifecycleEventArgs $eventArgs) {
        $recomputeChangeSet = false;
        $document = $eventArgs->getDocument();
        if($document instanceof RestoredByInterface){
            $document->setRestoredBy($this->identityName);
            $recomputeChangeSet = true;
        }
        if($document instanceof RestoredOnInterface){
            $document->setRestoredOn(time());
            $recomputeChangeSet = true;
        }
        if ($recomputeChangeSet) {
            $this->recomputeChangeset($eventArgs);
        }
    }
}