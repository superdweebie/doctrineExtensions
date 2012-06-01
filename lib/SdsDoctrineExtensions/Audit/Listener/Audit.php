<?php

namespace SdsDoctrineExtensions\Audit\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUserTrait;
use SdsDoctrineExtensions\Audit\Model\Audit as AuditModel;
use SdsDoctrineExtensions\Audit\Mapping\Driver\Audit as AuditDriver;
use SdsDoctrineExtensions\Common\Behaviour\AnnotationReaderTrait;
use SdsDoctrineExtensions\Common\AnnotationReaderInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsCommon\Audit\AuditedObjectInterface;
use SdsCommon\ActiveUser\ActiveUserInterface;

class Audit 
implements 
    EventSubscriber, 
    AnnotationReaderInterface, 
    ActiveUserInterface
{
    use ActiveUserTrait;
    use AnnotationReaderTrait;
               
    public function getSubscribedEvents(){
        return array(
            ODMEvents::loadClassMetadata,
            ODMEvents::onFlush
        );
    }  
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();                
        $driver = new AuditDriver($this->annotationReader);
        $driver->loadMetadataForClass($metadata);        
    }     
    
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();        
        
        foreach ($uow->getScheduledDocumentUpdates() AS $document) {
            if(!$document instanceof AuditedObjectInterface){
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