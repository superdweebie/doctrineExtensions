<?php

namespace SdsDoctrineExtensionsTest\Readonly\TestAsset;

use Doctrine\Common\EventSubscriber;
use SdsDoctrineExtensions\Readonly\Event\Events as ReadonlyEvents;
use SdsDoctrineExtensions\Readonly\Event\EventArgs;

class Subscriber implements EventSubscriber {

    protected $preCalled = false;
    protected $postCalled = false;
    protected $restoreInPre = false;

    public function getSubscribedEvents(){
        return array(
            ReadonlyEvents::preReadonlyRestore,
            ReadonlyEvents::postReadonlyRestore
        );
    }

    public function reset() {
        $this->preCalled = false;
        $this->postCalled = false;
        $this->restoreInPre = false;
    }

    public function preReadonlyRestore(EventArgs $eventArgs) {
        $this->preCalled = true;
        if ($this->restoreInPre) {
			$setMethod = 'set'.$eventArgs->getField();
            $eventArgs->getDocument()->$setMethod($eventArgs->getOriginalValue());
        }
    }

    public function postReadonlyRestore(EventArgs $eventArgs) {
        $this->postCalled = true;
    }

    public function getPreCalled() {
        return $this->preCalled;
    }

    public function getPostCalled() {
        return $this->postCalled;
    }

    public function getRestoreInPre() {
        return $this->restoreInPre;
    }

    public function setRestoreInPre($restoreInPre) {
        $this->restoreInPre = $restoreInPre;
    }
}
