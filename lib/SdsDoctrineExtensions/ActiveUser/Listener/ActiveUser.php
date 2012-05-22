<?php

namespace SdsDoctrineExtensions\ActiveUser\Listener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ODM\MongoDB\Event\LifecycleEventArgs,
    SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser as ActiveUserTrait,
    SdsDoctrineExtensions\Common\Utils;
use SdsDoctrineExtensions\Common\Listener\AbstractListener;

class ActiveUser extends AbstractListener
{
    use ActiveUserTrait;
    
    protected $activeUserTrait = 'SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser';
    
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        if($this->activeUser){
            $document = $eventArgs->getDocument();
            if(Utils::checkForTrait($document, $this->activeUserTrait)){
                $document->setActiveUser($this->activeUser);
            }
        }
    }
    
    public function getSubscribedEvents(){
        return ['postLoad'];
    } 
}
