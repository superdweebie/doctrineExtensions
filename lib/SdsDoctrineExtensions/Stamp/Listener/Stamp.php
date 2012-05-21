<?php

namespace SdsDoctrineExtensions\Stamp\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\LifecycleEventArgs,
    SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser as ActiveUserTrait,    
    SdsDoctrineExtensions\Common\Utils;

class Stamp implements EventSubscriber
{    
    use ActiveUserTrait;

    protected $createdByTrait = 'SdsDoctrineExtensions\Stamp\Behaviour\CreatedBy';  
    protected $createdOnTrait = 'SdsDoctrineExtensions\Stamp\Behaviour\CreatedOn';  
    protected $updatedByTrait = 'SdsDoctrineExtensions\Stamp\Behaviour\UpdatedBy';  
    protected $updatedOnTrait = 'SdsDoctrineExtensions\Stamp\Behaviour\UpdatedOn';  
    
    public function getSubscribedEvents(){
        return ['prePersist', 'preUpdate'];
    }      
    
    public function prePersist(LifecycleEventArgs $eventArgs)
    {        
        $doucment = $eventArgs->getDocument();
        if(Utils::checkForTrait($this, $this->createdByTrait)){
            $document->setCreatedBy($this->activeUser->getUsername()); 
        }
        if(Utils::checkForTrait($this, $this->createdOnTrait)){
            $document->setCreatedOn(time()); 
        }        
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {        
        $doucment = $eventArgs->getDocument();
        if(Utils::checkForTrait($this, $this->updatedByTrait)){
            $document->setUpdatedBy($this->activeUser->getUsername()); 
        }
        if(Utils::checkForTrait($this, $this->updatedOnTrait)){
            $document->setUpdatedOn(time()); 
        }        
    }    
}