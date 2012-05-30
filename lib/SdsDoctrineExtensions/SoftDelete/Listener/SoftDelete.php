<?php

namespace SdsDoctrineExtensions\SoftDelete\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\OnFlushEventArgs,
    Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs,
    SdsDoctrineExtensions\Common\Utils,
    SdsDoctrineExtensions\Audit\Model\Audit as AuditModel,
    SdsDoctrineExtensions\Audit\Mapping\Driver\Audit as AuditDriver,
    SdsDoctrineExtensions\SoftDelete\Events,
    Doctrine\ODM\MongoDB\Event\LifecycleEventArgs,
    Doctrine\ODM\MongoDB\Events as ODMEvents;
    SdsCommon\SoftDelete\SoftDeleteInterface;
    
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