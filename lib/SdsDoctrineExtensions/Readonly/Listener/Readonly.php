<?php

namespace SdsDoctrineExtensions\Readonly\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\OnFlushEventArgs,
    Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs,
    SdsDoctrineExtensions\Common\Utils,
    SdsDoctrineExtensions\Readonly\Mapping\Driver\Readonly as ReadonlyDriver,
    SdsDoctrineExtensions\Common\Behaviour\Reader;

class Readonly implements EventSubscriber
{
    use Reader;
               
    public function getSubscribedEvents(){
        return ['loadClassMetadata', 'onFlush'];
    }  
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();                
        $driver = new ReadonlyDriver($this->reader);
        $driver->loadMetadataForClass($metadata);        
    }     
    
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();        
        
        foreach ($uow->getScheduledDocumentUpdates() AS $document) {
            $changeSet = $uow->getDocumentChangeSet($document);
            $metadata = $dm->getClassMetadata(get_class($document));            
            foreach ($changeSet as $field => $change){
                $old = $change[0];
                $new = $change[1];                 
                if(isset($metadata->fieldMappings[$field][ReadonlyDriver::READONLY]) && 
                    $metadata->fieldMappings[$field][ReadonlyDriver::READONLY] && $old != null
                ){             
                    if($old != $new){                        
                        $document->{'set'.ucfirst($field)}($old);                   
                        $uow->recomputeSingleEntityChangeSet($metadata, $document);                    
                    }
                }
            }
        }                
    }  
}
