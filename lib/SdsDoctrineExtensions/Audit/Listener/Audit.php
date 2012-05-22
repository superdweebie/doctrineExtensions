<?php

namespace SdsDoctrineExtensions\Audit\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\OnFlushEventArgs,
    Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs,
    SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser as ActiveUserTrait,
    SdsDoctrineExtensions\Common\Utils,
    SdsDoctrineExtensions\Audit\Model\Audit as AuditModel,
    SdsDoctrineExtensions\Audit\Mapping\Driver\Audit as AuditDriver,
    SdsDoctrineExtensions\Common\Behaviour\Reader;

class Audit implements EventSubscriber
{
    use ActiveUserTrait, Reader;
    
    protected $auditTrait = 'SdsDoctrineExtensions\Audit\Behaviour\Audit';
           
    public function getSubscribedEvents(){
        return ['loadClassMetadata', 'onFlush'];
    }  
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();                
        $driver = new AuditDriver($this->reader);
        $driver->loadMetadataForClass($metadata);        
    }     
    
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();        
        
        foreach ($uow->getScheduledDocumentUpdates() AS $document) {
            if(!Utils::checkForTrait($document, $this->auditTrait)){
                continue;
            }
            $changeSet = $uow->getDocumentChangeSet($document);
            $metadata = $dm->getClassMetadata(get_class($document));            
            foreach ($changeSet as $field => $change){
                if(isset($metadata->fieldMappings[$field][AuditDriver::AUDIT]) && 
                    $metadata->fieldMappings[$field][AuditDriver::AUDIT]
                ){            
                    $old = $change[0];
                    $new = $change[1];            
                    if($old != $new){             
                        $audit = $this->createAudit($old, $new);
                        $document->addAudit($audit);                   
                        $uow->computeChangeSet($metadata, $document);                    
                    }
                }
            }
        }                
    }  
    
    protected function createAudit($old, $new){        
        $audit = new AuditModel();
        $audit->setOldValue($old);
        $audit->setNewValue($new);
        $audit->setChangedOn(time());
        if($this->activeUser){
            $audit->setChangedBy($this->activeUser->getUsername());            
        }
        return $audit;
    }
}