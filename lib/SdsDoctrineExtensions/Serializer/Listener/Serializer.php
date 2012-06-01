<?php

namespace SdsDoctrineExtensions\Serializer\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use SdsDoctrineExtensions\Serializer\Mapping\Driver\Serializer as SerializerDriver;
use SdsDoctrineExtensions\Common\Behaviour\AnnotationReaderTrait;
use SdsDoctrineExtensions\Common\AnnotationReaderInterface;
use Doctrine\ODM\MongoDB\Events as ODMEvents;

class Serializer implements EventSubscriber, AnnotationReaderInterface
{
    use AnnotationReaderTrait;
               
    public function getSubscribedEvents(){
        return array(
            ODMEvents::loadClassMetadata,
        );
    }  
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();                
        $driver = new SerializerDriver($this->annotationReader);
        $driver->loadMetadataForClass($metadata);        
    }         
}