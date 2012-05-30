<?php

namespace SdsDoctrineExtensions\Readonly\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use SdsDoctrineExtensions\Common\Utils;
use SdsDoctrineExtensions\Readonly\Mapping\Driver\Readonly as ReadonlyDriver;
use SdsDoctrineExtensions\Common\Behaviour\AnnotationReader;
use SdsDoctrineExtensions\Common\AnnotationReaderInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;

class Readonly implements EventSubscriber, AnnotationReaderInterface
{
    use AnnotationReader;
               
    public function getSubscribedEvents(){
        return array(
            ODMEvents::loadClassMetadata,
            ODMEvents::onFlush
        );
    }  
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();                
        $driver = new ReadonlyDriver($this->annotationReader);
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
