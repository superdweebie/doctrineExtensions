<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Sds\Common\Stamp\CreatedByInterface;
use Sds\Common\Stamp\CreatedOnInterface;
use Sds\Common\Stamp\UpdatedByInterface;
use Sds\Common\Stamp\UpdatedOnInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;

/**
 * Adds create and update stamps during persist
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber extends AbstractStampSubscriber {

    /**
     *
     * @return array
     */
    public function getSubscribedEvents() {
        return array(
            ODMEvents::prePersist,
            ODMEvents::preUpdate
        );
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs) {
        $document = $eventArgs->getDocument();
        if($document instanceof CreatedByInterface){
            $document->setCreatedBy($this->identityName);
        }
        if($document instanceof CreatedOnInterface){
            $document->setCreatedOn(time());
        }
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs) {
        $recomputeChangeSet = false;
        $document = $eventArgs->getDocument();
        if ($document instanceof UpdatedByInterface) {
            $document->setUpdatedBy($this->identityName);
            $recomputeChangeSet = true;
        }
        if ($document instanceof UpdatedOnInterface) {
            $document->setUpdatedOn(time());
            $recomputeChangeSet = true;
        }

        if ($recomputeChangeSet) {
            $this->recomputeChangeset($eventArgs);
        }
    }
}