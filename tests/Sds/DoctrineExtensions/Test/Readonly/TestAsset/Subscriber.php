<?php

namespace Sds\DoctrineExtensions\Test\Readonly\TestAsset;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\Readonly\Events as ReadonlyEvents;
use Sds\DoctrineExtensions\Readonly\EventArgs;

class Subscriber implements EventSubscriber {

    protected $calls = array();

    protected $rollbackInPre = false;

    public function getSubscribedEvents(){
        return array(
            ReadonlyEvents::preReadonlyRollback,
            ReadonlyEvents::postReadonlyRollback
        );
    }

    public function reset() {
        $this->calls = array();
        $this->rollbackInPre = false;
    }

    public function preReadonlyRollback(EventArgs $eventArgs) {
        $this->calls['preReadonlyRollback'] = $eventArgs;
        if ($this->rollbackInPre) {
			$setMethod = 'set'.$eventArgs->getField();
            $eventArgs->getDocument()->$setMethod($eventArgs->getOriginalValue());
        }
    }

    public function getRollbackInPre() {
        return $this->rollbackInPre;
    }

    public function setRollbackInPre($rollbackInPre) {
        $this->rollbackInPre = $rollbackInPre;
    }

    public function getCalls() {
        return $this->calls;
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments[0];
    }
}
