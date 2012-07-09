<?php

namespace Sds\DoctrineExtensions\Test\Freeze\TestAsset;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\Freeze\Event\Events as FreezeEvents;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

class Subscriber implements EventSubscriber {

    protected $preFreezeCalled = false;
    protected $postFreezeCalled = false;
    protected $preThawCalled = false;
    protected $postThawCalled = false;
    protected $frozenUpdateDeniedCalled = false;
    protected $frozenDeleteDeniedCalled = false;

    protected $rollbackFreeze = false;
    protected $rollbackThaw = false;

    public function getSubscribedEvents(){
        return array(
            FreezeEvents::preFreeze,
            FreezeEvents::postFreeze,
            FreezeEvents::preThaw,
            FreezeEvents::postThaw,
            FreezeEvents::frozenUpdateDenied,
            FreezeEvents::frozenDeleteDenied
        );
    }

    public function reset() {
        $this->preFreezeCalled = false;
        $this->postFreezeCalled = false;
        $this->preThawCalled = false;
        $this->postThawCalled = false;
        $this->frozenUpdateDeniedCalled = false;
        $this->frozenDeleteDeniedCalled = false;

        $this->rollbackFreeze = false;
        $this->rollbackThaw = false;
    }

    public function preFreeze(LifecycleEventArgs $eventArgs) {
        $this->preFreezeCalled = true;
        if ($this->rollbackFreeze) {
            $eventArgs->getDocument()->thaw();
        }
    }

    public function postFreeze(LifecycleEventArgs $eventArgs) {
        $this->postFreezeCalled = true;
    }

    public function preThaw(LifecycleEventArgs $eventArgs) {
        $this->preThawCalled = true;
        if ($this->rollbackThaw) {
            $eventArgs->getDocument()->Freeze();
        }
    }

    public function postThaw(LifecycleEventArgs $eventArgs) {
        $this->postThawCalled = true;
    }

    public function frozenUpdateDenied(LifecycleEventArgs $eventArgs) {
        $this->frozenUpdateDeniedCalled = true;
    }

    public function frozenDeleteDenied(LifecycleEventArgs $eventArgs) {
        $this->frozenDeleteDeniedCalled = true;
    }

    public function setRollbackFreeze($rollbackFreeze) {
        $this->rollbackFreeze = $rollbackFreeze;
    }

    public function setRollbackThaw($rollbackThaw) {
        $this->rollbackThaw = $rollbackThaw;
    }

    public function getPreFreezeCalled() {
        return $this->preFreezeCalled;
    }

    public function getPostFreezeCalled() {
        return $this->postFreezeCalled;
    }

    public function getPreThawCalled() {
        return $this->preThawCalled;
    }

    public function getPostThawCalled() {
        return $this->postThawCalled;
    }

    public function getFrozenUpdateDeniedCalled() {
        return $this->frozenUpdateDeniedCalled;
    }

    public function getFrozenDeleteDeniedCalled() {
        return $this->frozenDeleteDeniedCalled;
    }
}
