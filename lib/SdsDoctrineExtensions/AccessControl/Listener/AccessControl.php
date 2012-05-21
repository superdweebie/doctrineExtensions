<?php

namespace SdsDoctrineExtensions\AccessControl\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\OnFlushEventArgs,
    SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser as ActiveUserTrait,    
    SdsDoctrineExtensions\Common\Utils,
    SdsDoctrineExtensions\AccessControl\Model\Permission,
    Doctrine\ODM\MongoDB\Events as ODMEvents,
    SdsDoctrineExtensions\SoftDelete\Events as SoftDeleteEvents,
    SdsDoctrineExtensions\AccessControl\Events as AccessControlEvents,    
    Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

class AccessControl implements EventSubscriber
{    
    use ActiveUserTrait;
    
    protected $documentAccessControlTrait = 'SdsDoctrineExtensions\AccessControl\Behaviour\DocumentAccessControl';
    protected $userAccessControlTrait = 'SdsDoctrineExtensions\AccessControl\Behaviour\UserAccessControl';
    protected $softDeleteTrait = 'SdsDoctrineExtensions\SoftDelete\Behaviour\SoftDelete';    
    
    public function getSubscribedEvents(){
        return [
            ODMEvents::onFlush,
            SoftDeleteEvents::preSoftDelete,
            SoftDeleteEvents::preSoftRestore
        ];
    }      
    
    public function preSoftDelete(LifecycleEventArgs $eventArgs){
        $doucment = $eventArgs->getDocument();
        
        if(Utils::checkForTrait($document, $this->documentAccessControlTrait)){
            if(!$document->isActionAllowed(Permission::ACTION_DELETE, null, $this->activeUser)){                    
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
        
        if(Utils::checkForTrait($document, $this->documentAccessControlTrait)){
            if(!$document->isActionAllowed(Permission::ACTION_RESTORE, null, $this->activeUser)){                    
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
        
        if(!Utils::checkForTrait($this->activeUser, $this->userAccessControlTrait)){            
            throw new \Exception('activeUser must exhibit the '.$this->userAccessControlTrait.' trait');                
        }
                
        foreach ($uow->getScheduledDocumentInsertions() AS $document) {
            if(Utils::checkForTrait($document, $this->documentAccessControlTrait)){
                if(!$document->isActionAllowed(Permission::ACTION_CREATE, null, $this->activeUser)){                    
                    $uow->detach($document);  
          
                    if ($evm->hasListeners(AccessControlEvents::insertDenied)) {
                        $evm->dispatchEvent(AccessControlEvents::insertDenied, new LifecycleEventArgs($document, $dm));
                    }                      
                }
            } 
        }

        foreach ($uow->getScheduledDocumentUpdates() AS $document) {
            if(Utils::checkForTrait($document, $this->documentAccessControlTrait)){
                if(!$document->isActionAllowed(Permission::ACTION_UPDATE, null, $this->activeUser)){                    
                    $uow->detach($document);   
                    
                    if ($evm->hasListeners(AccessControlEvents::updateDenied)) {
                        $evm->dispatchEvent(AccessControlEvents::updateDenied, new LifecycleEventArgs($document, $dm));
                    }                    
                }
            }              
        }       
        
        foreach ($uow->getScheduledDocumentDeletions() AS $document) {
            if(Utils::checkForTrait($document, $this->documentAccessControlTrait)){
                if(!$document->isActionAllowed(Permission::ACTION_DELETE, null, $this->activeUser)){
                    $uow->detach($document);    
                    
                    if ($evm->hasListeners(AccessControlEvents::deleteDenied)) {
                        $evm->dispatchEvent(AccessControlEvents::deleteDenied, new LifecycleEventArgs($document, $dm));
                    }                    
                }
            }             
        }                 
    }      
}