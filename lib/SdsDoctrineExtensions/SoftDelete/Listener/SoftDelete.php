<?php

namespace SdsDoctrineExtensions\SoftDelete\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\OnFlushEventArgs,
    Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs,
    SdsDoctrineExtensions\Common\Utils,
    SdsDoctrineExtensions\Audit\Model\Audit as AuditModel,
    SdsDoctrineExtensions\Audit\Mapping\Driver\Audit as AuditDriver;

class SoftDelete implements EventSubscriber
{
    protected $softDeleteTrait = 'SdsDoctrineExtensions\SoftDelete\Behaviour\SoftDelete';
           
    public function getSubscribedEvents(){
        return ['onFlush'];
    }         
    
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();        
        
        foreach ($uow->getScheduledDocumentDeletions() AS $document) {
            if(!Utils::checkForTrait($document, $this->softDeleteTrait)){
                continue;
            }            
            $dm->persist($document);
            $uow->propertyChanged($document, 'deleted', false, true);
            $uow->scheduleExtraUpdate(
                $document, 
                array(
                    'deleted' => array(false, true)
                )
            );                        
        }                
    }     
}