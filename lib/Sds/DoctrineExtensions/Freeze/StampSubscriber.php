<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Sds\Common\Freeze\FrozenByInterface;
use Sds\Common\Freeze\FrozenOnInterface;
use Sds\Common\Freeze\ThawedByInterface;
use Sds\Common\Freeze\ThawedOnInterface;
use Sds\DoctrineExtensions\Freeze\Events as FreezeEvents;
use Sds\DoctrineExtensions\Stamp\AbstractStampSubscriber;

/**
 * Adds freeze and thaw stamps during persist
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
            FreezeEvents::postFreeze,
            FreezeEvents::postThaw
        );
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs
     */
    public function postFreeze(LifecycleEventArgs $eventArgs) {
        $recomputeChangeSet = false;
        $document = $eventArgs->getDocument();
        if($document instanceof FrozenByInterface){
            $document->setFrozenBy($this->activeUser->getUsername());
            $recomputeChangeSet = true;
        }
        if($document instanceof FrozenOnInterface){
            $document->setFrozenOn(time());
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
    public function postThaw(LifecycleEventArgs $eventArgs) {
        $recomputeChangeSet = false;
        $document = $eventArgs->getDocument();
        if($document instanceof ThawedByInterface){
            $document->setThawedBy($this->activeUser->getUsername());
            $recomputeChangeSet = true;
        }
        if($document instanceof ThawedOnInterface){
            $document->setThawedOn(time());
            $recomputeChangeSet = true;
        }
        if ($recomputeChangeSet) {
            $this->recomputeChangeset($eventArgs);
        }
    }
}