<?php

namespace SdsDoctrineExtensions\Metadata\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use SdsDoctrineExtensions\Common\Utils;
use SdsDoctrineExtensions\Metadata\Mapping\Driver\Metadata as MetadataDriver;
use SdsDoctrineExtensions\Common\Behaviour\AnnotationReaderTrait;
use SdsDoctrineExtensions\Common\AnnotationReaderInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;

class Metadata implements EventSubscriber, AnnotationReaderInterface
{
    use AnnotationReaderTrait;
               
    public function getSubscribedEvents(){
        return array(
            ODMEvents::loadClassMetadata
        );
    }  
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();                
        $driver = new MetadataDriver($this->annotationReader);
        $driver->loadMetadataForClass($metadata);        
    }           
}
