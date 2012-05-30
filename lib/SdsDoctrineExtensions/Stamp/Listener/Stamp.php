<?php

namespace SdsDoctrineExtensions\Stamp\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser as ActiveUserTrait;
use SdsCommon\ActiveUser\ActiveUserInterface;
use SdsCommon\Stamp\CreatedByInterface;
use SdsCommon\Stamp\CreatedOnInterface;
use SdsCommon\Stamp\UpdatedByInterface;
use SdsCommon\Stamp\UpdatedOnInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
        
class Stamp implements EventSubscriber, ActiveUserInterface
{    
    use ActiveUserTrait;
   
    public function getSubscribedEvents(){
        return array(
            ODMEvents::prePersist,
            ODMEvents::preUpdate
        );        
    }      
    
    public function prePersist(LifecycleEventArgs $eventArgs)
    {        
        $document = $eventArgs->getDocument();
        if($document instanceof CreatedByInterface){
            $document->setCreatedBy($this->activeUser->getUsername()); 
        }
        if($document instanceof CreatedOnInterface){
            $document->setCreatedOn(time()); 
        }        
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {        
        $document = $eventArgs->getDocument();
        if($document instanceof UpdatedByInterface){
            $document->setUpdatedBy($this->activeUser->getUsername()); 
        }
        if($document instanceof UpdatedOnInterface){
            $document->setUpdatedOn(time()); 
        }        
    }    
}