<?php

namespace SdsDoctrineExtensions\SoftDelete\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use SdsDoctrineExtensions\Audit\Model\Audit as AuditModel;
use SdsDoctrineExtensions\Audit\Mapping\Driver\Audit as AuditDriver;
use SdsDoctrineExtensions\SoftDelete\Events;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsCommon\SoftDelete\SoftDeleteInterface;
    
class SoftDelete implements EventSubscriber
{
           
    public function getSubscribedEvents(){
        return [ODMEvents::onFlush];
    }         
        
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();        
        $evm = $dm->getEventManager();
        
        foreach ($uow->getScheduledDocumentUpdates() AS $document) {
            if($document instanceof SoftDeleteInterface){                
                $changeSet = $uow->getDocumentChangeSet($document);           
                if(
                    isset($changeSet['isDeleted']) && 
                    $changeSet['isDeleted'][1]
                ){                    
                    // Raise preSoftDelete            
                    if ($evm->hasListeners(Events::preSoftDelete)) {
                        $evm->dispatchEvent(Events::preSoftDelete, new LifecycleEventArgs($document, $dm));
                    }  
                    
                    if($document->getIsDeleted()){
                        // Raise postSoftDelete            
                        if ($evm->hasListeners(Events::postSoftDelete)) {
                            $evm->dispatchEvent(Events::postSoftDelete, new LifecycleEventArgs($document, $dm));
                        }  
                    } else {
                        $metadata = $dm->getClassMetadata(get_class($document));                         
                        $uow->computeSingleDocumentChangeSet($metadata, $document);                         
                    }
                }          
                if(
                    isset($changeSet['isDeleted']) && 
                    !$changeSet['isDeleted'][1]
                ){                    
                    // Raise preSoftRestore            
                    if ($evm->hasListeners(Events::preSoftRestore)) {
                        $evm->dispatchEvent(Events::preSoftRestore, new LifecycleEventArgs($document, $dm));
                    }  
                    
                    if(!$document->getIsDeleted()){
                        // Raise postSoftRestore            
                        if ($evm->hasListeners(Events::postSoftRestore)) {
                            $evm->dispatchEvent(Events::postSoftRestore, new LifecycleEventArgs($document, $dm));
                        }  
                    } else {
                        $metadata = $dm->getClassMetadata(get_class($document));                         
                        $uow->computeSingleDocumentChangeSet($metadata, $document);                         
                    }
                }                                
            }             
        }   
        
        foreach ($uow->getScheduledDocumentDeletions() AS $document) {
            if(!$document instanceof SoftDeleteInterface){ 
                continue;
            }   
            $document->setIsDeleted(true);
            
            // Raise preSoftDelete            
            if ($evm->hasListeners(Events::preSoftDelete)) {
                $evm->dispatchEvent(Events::preSoftDelete, new LifecycleEventArgs($document, $dm));
            }
        
            if($document->getIsDeleted()){
                $dm->persist($document);
                $uow->propertyChanged($document, 'deleted', false, true);
                $uow->scheduleExtraUpdate(
                    $document, 
                    array(
                        'deleted' => array(false, true)
                    )
                ); 

                // Raise postSoftDelete            
                if ($evm->hasListeners(Events::postSoftDelete)) {
                    $evm->dispatchEvent(Events::postSoftDelete, new LifecycleEventArgs($document, $dm));
                } 
            }
        }                
    }     
}