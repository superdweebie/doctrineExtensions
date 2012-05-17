<?php

namespace SdsDoctrineExtensions\Serializer\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs,
    SdsDoctrineExtensions\Common\Utils,
    SdsDoctrineExtensions\Serializer\Mapping\Driver\Serializer as SerializerDriver,
    SdsDoctrineExtensions\Common\Reader;

class Serializer implements EventSubscriber
{
    use Reader;
               
    public function getSubscribedEvents(){
        return ['loadClassMetadata'];
    }  
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();                
        $driver = new SerializerDriver($this->reader);
        $driver->loadMetadataForClass($metadata);        
    }         
}