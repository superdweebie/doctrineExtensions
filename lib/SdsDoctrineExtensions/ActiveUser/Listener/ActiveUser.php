<?php

namespace SdsDoctrineExtensions\ActiveUser\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUserTrait;
use SdsCommon\ActiveUser\ActiveUserInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
    
class ActiveUser implements EventSubscriber, ActiveUserInterface
{
    use ActiveUserTrait;
        
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
