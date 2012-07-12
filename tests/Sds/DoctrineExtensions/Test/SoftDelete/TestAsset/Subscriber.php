<?php

namespace Sds\DoctrineExtensions\Test\SoftDelete\TestAsset;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\SoftDelete\Events as SoftDeleteEvents;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

class Subscriber implements EventSubscriber {

    protected $calls = array();

    protected $rollbackDelete = false;
    protected $rollbackRestore = false;

    public function getSubscribedEvents(){
        return array(
            SoftDeleteEvents::preSoftDelete,
            SoftDeleteEvents::postSoftDelete,
            SoftDeleteEvents::preRestore,
            SoftDeleteEvents::postRestore,
            SoftDeleteEvents::softDeletedUpdateDenied
        );
    }

    public function reset() {
        $this->calls = array();
        $this->rollbackDelete = false;
        $this->rollbackRestore = false;
    }

    public function preSoftDelete(LifecycleEventArgs $eventArgs) {
        $this->calls[SoftDeleteEvents::preSoftDelete] = $eventArgs;
        if ($this->rollbackDelete) {
            $eventArgs->getDocument()->restore();
        }
    }

    public function preRestore(LifecycleEventArgs $eventArgs) {
        $this->calls[SoftDeleteEvents::preRestore] = $eventArgs;
        if ($this->rollbackRestore) {
            $eventArgs->getDocument()->softDelete();
        }
    }

    public function getRollbackDelete() {
        return $this->rollbackDelete;
    }

    public function setRollbackDelete($rollbackDelete) {
        $this->rollbackDelete = $rollbackDelete;
    }

    public function getRollbackRestore() {
        return $this->rollbackRestore;
    }

    public function setRollbackRestore($rollbackRestore) {
        $this->rollbackRestore = $rollbackRestore;
    }

    public function getCalls() {
        return $this->calls;
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments[0];
    }
}
