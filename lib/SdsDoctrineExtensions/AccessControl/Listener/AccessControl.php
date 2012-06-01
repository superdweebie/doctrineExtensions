<?php

namespace SdsDoctrineExtensions\AccessControl\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUserTrait;    
use SdsDoctrineExtensions\AccessControl\Model\Permission;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsDoctrineExtensions\SoftDelete\Events as SoftDeleteEvents;
use SdsDoctrineExtensions\AccessControl\Events as AccessControlEvents;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use SdsCommon\AccessControl\ControlledObjectInterface;
use SdsCommon\User\UserInterface;
use SdsCommon\AccessControl\Constant\Action;
use SdsCommon\ActiveUser\ActiveUserInterface;

class AccessControl implements EventSubscriber, ActiveUserInterface
{    
    use ActiveUserTrait;
      
    public function getSubscribedEvents(){
        return array(
            ODMEvents::onFlush,
            SoftDeleteEvents::preSoftDelete,
            SoftDeleteEvents::preSoftRestore
        );
    }      
    
    public function preSoftDelete(LifecycleEventArgs $eventArgs){
        $doucment = $eventArgs->getDocument();
        
        if($document instanceof ControlledObjectInterface){
            if(!$document->isActionAllowed(Action::DELETE, null, $this->activeUser)){                    
                $document->setIsDeleted(false);
                
                if ($evm->hasListeners(AccessControlEvents::deleteDenied)) {
                    $dm = $eventArgs->getDocumentManager(); 
                    $evm = $dm->getEventManager();                    
                    $evm->dispatchEvent(AccessControlEvents::deleteDenied, new LifecycleEventArgs($document, $dm));
                }                  
            }
        }                 
    }
    
    public function preSoftRestore(LifecycleEventArgs $eventArgs){
        $doucment = $eventArgs->getDocument();
        
        if($document instanceof ControlledObjectInterface){
            if(!$document->isActionAllowed(Action::RESTORE, null, $this->activeUser)){                    
                $document->setIsDeleted(true);    

                if ($evm->hasListeners(AccessControlEvents::restoreDenied)) {
                    $dm = $eventArgs->getDocumentManager(); 
                    $evm = $dm->getEventManager();                     
                    $evm->dispatchEvent(AccessControlEvents::restoreDenied, new LifecycleEventArgs($document, $dm));
                }                  
            }
        }                 
    }
    
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();        
        $evm = $dm->getEventManager();
        
        if(!$this->activeUser instanceOf UserInterface){            
            throw new \Exception('activeUser must exhibit the UserInterface');                
        }
                
        foreach ($uow->getScheduledDocumentInsertions() AS $document) {
            if($document instanceof ControlledObjectInterface){
                if(!$document->isActionAllowed(Action::CREATE, null, $this->activeUser)){                    
                    $uow->detach($document);  
          
                    if ($evm->hasListeners(AccessControlEvents::insertDenied)) {
                        $evm->dispatchEvent(AccessControlEvents::insertDenied, new LifecycleEventArgs($document, $dm));
                    }                      
                }
            } 
        }

        foreach ($uow->getScheduledDocumentUpdates() AS $document) {
            if($document instanceof ControlledObjectInterface){
                if(!$document->isActionAllowed(Action::UPDATE, null, $this->activeUser)){                    
                    $uow->detach($document);   
                    
                    if ($evm->hasListeners(AccessControlEvents::updateDenied)) {
                        $evm->dispatchEvent(AccessControlEvents::updateDenied, new LifecycleEventArgs($document, $dm));
                    }                    
                }
            }              
        }       
        
        foreach ($uow->getScheduledDocumentDeletions() AS $document) {
            if($document instanceof ControlledObjectInterface){
                if(!$document->isActionAllowed(Action::DELETE, null, $this->activeUser)){
                    $uow->detach($document);    
                    
                    if ($evm->hasListeners(AccessControlEvents::deleteDenied)) {
                        $evm->dispatchEvent(AccessControlEvents::deleteDenied, new LifecycleEventArgs($document, $dm));
                    }                    
                }
            }             
        }                 
    }      
}