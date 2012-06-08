<?php

namespace SdsDoctrineExtensions\ActiveUser\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use SdsCommon\ActiveUser\ActiveUserAwareTrait;
use SdsCommon\ActiveUser\ActiveUserAwareInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
    
class ActiveUser implements EventSubscriber, ActiveUserAwareInterface
{
    use ActiveUserAwareTrait;
        
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        if($this->activeUser){
            $document = $eventArgs->getDocument();
            if($document instanceof ActiveUserInterface){
                $document->setActiveUser($this->activeUser);
            }
        }
    }
    
    public function getSubscribedEvents(){
        return array(
            ODMEvents::postLoad,
        );
    } 
}
