<?php

namespace SdsDoctrineExtensions\AccessControl\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\OnFlushEventArgs,
    SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser as ActiveUserTrait,    
    SdsDoctrineExtensions\Common\Utils,
    SdsDoctrineExtensions\AccessControl\Model\Permission;

class AccessControl implements EventSubscriber
{    
    use ActiveUserTrait;
    
    protected $documentAccessControlTrait = 'SdsDoctrineExtensions\AccessControl\Behaviour\DocumentAccessControl';
    protected $userAccessControlTrait = 'SdsDoctrineExtensions\AccessControl\Behaviour\UserAccessControl';
    
    public function getSubscribedEvents(){
        return ['onFlush'];
    }      
    
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();        

        if(!Utils::checkForTrait($this->activeUser, $this->userAccessControlTrait)){            
            throw new \Exception('activeUser must exhibit the '.$this->userAccessControlTrait.' trait');                
        }
                
        foreach ($uow->getScheduledDocumentInsertions() AS $document) {
            if(Utils::checkForTrait($document, $this->documentAccessControlTrait)){
                if(!$document->isActionAllowed(Permission::ACTION_CREATE, null, $this->activeUser)){                    
                    $uow->detach($document);                       
                }
            } 
        }

        foreach ($uow->getScheduledDocumentUpdates() AS $document) {
            if(Utils::checkForTrait($document, $this->documentAccessControlTrait)){
                if(!$document->isActionAllowed(Permission::ACTION_UPDATE, null, $this->activeUser)){                    
                    $uow->detach($document);                      
                }
            } 
        }       
        
        foreach ($uow->getScheduledDocumentDeletions() AS $document) {
            if(Utils::checkForTrait($document, $this->documentAccessControlTrait)){
                if(!$document->isActionAllowed(Permission::ACTION_DELETE, null, $this->activeUser)){
                    $uow->detach($document);    
                }
            }             
        }                 
    }      
}