<?php

namespace SdsDoctrineExtensions\Metadata\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use SdsDoctrineExtensions\Common\Utils;
use SdsDoctrineExtensions\Metadata\Mapping\Driver\Metadata as MetadataDriver;
use SdsDoctrineExtensions\Common\Behaviour\AnnotationReader;

class Metadata implements EventSubscriber
{
    use AnnotationReader;
               
    public function getSubscribedEvents(){
        return ['loadClassMetadata'];
    }  
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();                
        $driver = new MetadataDriver($this->annotationReader);
        $driver->loadMetadataForClass($metadata);        
    }           
}
