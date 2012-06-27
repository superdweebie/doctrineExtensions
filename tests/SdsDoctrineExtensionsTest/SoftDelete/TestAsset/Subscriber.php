<?php

namespace SdsDoctrineExtensionsTest\SoftDelete\TestAsset;

use Doctrine\Common\EventSubscriber;
use SdsDoctrineExtensions\SoftDelete\Event\Events as SoftDeleteEvents;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

class Subscriber implements EventSubscriber {

    protected $preDeleteCalled = false;
    protected $postDeleteCalled = false;
    protected $preRestoreCalled = false;
    protected $postRestoreCalled = false;
    protected $softDeleteUpdateDeniedCalled = false;

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
        $this->preDeleteCalled = false;
        $this->postDeleteCalled = false;
        $this->preRestoreCalled = false;
        $this->postRestoreCalled = false;
        $this->softDeleteUpdateDeniedCalled = false;
        $this->rollbackDelete = false;
        $this->rollbackRestore = false;
    }

    public function preSoftDelete(LifecycleEventArgs $eventArgs) {
        $this->preDeleteCalled = true;
        if ($this->rollbackDelete) {
            $eventArgs->getDocument()->restore();
        }
    }

    public function postSoftDelete(LifecycleEventArgs $eventArgs) {
        $this->postDeleteCalled = true;
    }

    public function preRestore(LifecycleEventArgs $eventArgs) {
        $this->preRestoreCalled = true;
        if ($this->rollbackRestore) {
            $eventArgs->getDocument()->softDelete();
        }
    }

    public function postRestore(LifecycleEventArgs $eventArgs) {
        $this->postRestoreCalled = true;
    }

    public function softDeletedUpdateDenied(LifecycleEventArgs $eventArgs) {
        $this->softDeleteUpdateDeniedCalled = true;
    }

    public function setRollbackDelete($rollbackDelete) {
        $this->rollbackDelete = $rollbackDelete;
    }

    public function setRollbackRestore($rollbackRestore) {
        $this->rollbackRestore = $rollbackRestore;
    }

    public function getPreDeleteCalled() {
        return $this->preDeleteCalled;
    }

    public function getPostDeleteCalled() {
        return $this->postDeleteCalled;
    }

    public function getpreRestoreCalled() {
        return $this->preRestoreCalled;
    }

    public function getpostRestoreCalled() {
        return $this->postRestoreCalled;
    }

    public function getSoftDeleteUpdateDeniedCalled() {
        return $this->softDeleteUpdateDeniedCalled;
    }
}
