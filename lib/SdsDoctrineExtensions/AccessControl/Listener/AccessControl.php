<?php

namespace SdsDoctrineExtensions\AccessControl\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\OnFlushEventArgs,
    SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser as ActiveUserTrait,    
    SdsDoctrineExtensions\AccessControl\Model\Permission,
    Doctrine\ODM\MongoDB\Events as ODMEvents,
    SdsDoctrineExtensions\SoftDelete\Events as SoftDeleteEvents,
    SdsDoctrineExtensions\AccessControl\Events as AccessControlEvents,    
    Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use SdsCommon\AccessControl\ControlledObjectInterface;
use SdsCommon\AccessControl\UserInterface;
use SdsCommon\AccessControl\Constant\Action;

class AccessControl implements EventSubscriber
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