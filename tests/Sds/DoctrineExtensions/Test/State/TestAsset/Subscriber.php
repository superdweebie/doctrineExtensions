<?php

namespace Sds\DoctrineExtensions\Test\State\TestAsset;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\State\EventArgs as StateEventArgs;
use Sds\DoctrineExtensions\State\Events as StateEvents;

class Subscriber implements EventSubscriber {

    protected $calls = array();

    protected $rollbackTransition = false;

    public function getSubscribedEvents(){
        return array(
            StateEvents::preTransition,
            StateEvents::onTransition,
            StateEvents::postTransition
        );
    }

    public function reset() {
        $this->calls = array();
        $this->rollbackTransition = false;
    }

    public function preTransition(StateEventArgs $eventArgs) {
        $this->calls['preTransition'] = $eventArgs;
        if ($this->rollbackTransition) {
            $eventArgs->getDocument()->setState($eventArgs->getTransition()->getFromState());
        }
    }

    public function getRollbackTransition() {
        return $this->rollbackTransition;
    }

    public function setRollbackTransition($rollbackTransition) {
        $this->rollbackTransition = $rollbackTransition;
    }

    public function getCalls() {
        return $this->calls;
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments[0];
    }
}
