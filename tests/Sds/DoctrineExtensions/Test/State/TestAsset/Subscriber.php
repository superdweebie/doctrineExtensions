<?php

namespace Sds\DoctrineExtensions\Test\State\TestAsset;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\State\EventArgs as StateEventArgs;
use Sds\DoctrineExtensions\State\Events as StateEvents;

class Subscriber implements EventSubscriber {

    protected $calls = array();

    protected $rollbackStateChange = false;

    public function getSubscribedEvents(){
        return array(
            StateEvents::preStateChange,
            StateEvents::onStateChange,
            StateEvents::postStateChange
        );
    }

    public function reset() {
        $this->calls = array();
        $this->rollbackStateChange = false;
    }

    public function preStateChange(StateEventArgs $eventArgs) {
        $this->calls['preStateChange'] = $eventArgs;
        if ($this->rollbackStateChange) {
            $eventArgs->getDocument()->setState($eventArgs->getFromState());
        }
    }

    public function getRollbackStateChange() {
        return $this->rollbackStateChange;
    }

    public function setRollbackStateChange($rollbackStateChange) {
        $this->rollbackStateChange = $rollbackStateChange;
    }

    public function getCalls() {
        return $this->calls;
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments[0];
    }
}