<?php

namespace SdsDoctrineExtensions\Serializer\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs,
    SdsDoctrineExtensions\Common\Utils,
    SdsDoctrineExtensions\Serializer\Mapping\Driver\Serializer as SerializerDriver,
    SdsDoctrineExtensions\Common\Behaviour\AnnotationReader;

class Serializer implements EventSubscriber
{
    use AnnotationReader;
               
    public function getSubscribedEvents(){
        return ['loadClassMetadata'];
    }  
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();                
        $driver = new SerializerDriver($this->annotationReader);
        $driver->loadMetadataForClass($metadata);        
    }         
}