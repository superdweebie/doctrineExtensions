<?php

namespace SdsDoctrineExtensionsTest\DoNotHardDelete\TestAsset;

use Doctrine\Common\EventSubscriber;
use SdsDoctrineExtensions\DoNotHardDelete\Event\Events as DoNotHardDeleteEvents;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

class Subscriber implements EventSubscriber {

    protected $hardDeleteRefusedCalled = false;

    public function getSubscribedEvents(){
        return array(
            DoNotHardDeleteEvents::hardDeleteRefused
        );
    }

    public function reset() {
        $this->hardDeleteRefusedCalled = false;
    }

    public function hardDeleteRefused(LifecycleEventArgs $eventArgs) {
        $this->hardDeleteRefusedCalled = true;
    }

    public function getHardDeleteRefusedCalled() {
        return $this->hardDeleteRefusedCalled;
    }
}
