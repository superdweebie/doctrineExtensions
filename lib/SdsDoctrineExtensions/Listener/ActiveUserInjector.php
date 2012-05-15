<?php

namespace SdsDoctrineExtensions\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\LifecycleEventArgs,
    SdsDoctrineExtensions\Behaviour\ActiveUser as ActiveUserTrait,
    SdsDoctrineExtensions\Utils;

class ActiveUserInjector implements EventSubscriber
{
    use ActiveUserTrait;
    
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        if($this->activeUser){
            $document = $eventArgs->getDocument();
            if(Utils::checkForTrait($document, 'SdsDoctrineExtensions\Behaviour\ActiveUser')){
                $document->setActiveUser($this->activeUser);
            }
        }
    }
    
    public function getSubscribedEvents(){
        return ['postLoad'];
    } 
}
