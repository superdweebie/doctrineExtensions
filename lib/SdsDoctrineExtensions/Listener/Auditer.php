<?php

namespace SdsDoctrineExtensions\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs,
    Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs,
    SdsDoctrineExtensions\Behaviour\ActiveUser as ActiveUserTrait,
    SdsDoctrineExtensions\Utils,
    SdsDoctrineExtensions\Model\Audit;

class Auditer implements EventSubscriber
{
    use ActiveUserTrait;
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        if(isset($metadata->fieldMappings['eIAC'])){
            $metadata->fieldMappings['eIAC']['test'] = 'test';
        }
        
$reflClass = $metadata->reflClass;
//ew ReflectionClass('MyCompany\Entity\User');
//$classAnnotations = $reader->getClassAnnotations($reflClass);
//
//foreach ($classAnnotations AS $annot) {
//    if ($annot instanceof \MyCompany\Annotations\Foo) {
//        echo $annot->bar; // prints "foo";
//    } else if ($annot instanceof \MyCompany\Annotations\Bar) {
//        echo $annot->foo; // prints "bar";
//    }
//}

    }     
    
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $document = $eventArgs->getDocument();
        $metadata = $dm->getClassMetadata(get_class($document));
        $changeSet = $eventArgs->getDocumentChangeSet();
        foreach ($changeSet as $field => $change){
            $old = $change[0];
            $new = $change[1];            
            if($old != $new){
                
            }
        }
    }
    
    public function getSubscribedEvents(){
        return ['loadClassMetadata', 'preUpdate'];
    }    
    
    protected function createAudit($old, $new){
        
//        if($this->activeUser){
//            $document = $eventArgs->getDocument();
//            
//            $traits = Utils::getAllTraits($document);
//            if(isset($traits['SdsDoctrineExtensions\Behaviour\ActiveUser'])){
//                $document->setActiveUser($this->activeUser);
//            }
//        }
//        
//
//        $newValues = get_object_vars($this);
//        $oldValues = $this->oldValues;        
//        foreach($this->propertiesToAudit as $propertyToAudit){
//            if($oldValues[$propertyToAudit] != $newValues[$propertyToAudit]){
//                $this->audits[] = $propertyToAudit;
//            }
//        }
    
    }
}
