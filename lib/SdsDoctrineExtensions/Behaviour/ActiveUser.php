<?php

namespace SdsDoctrineExtensions\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Utils;

trait ActiveUser {
   
    protected $activeUser;
        
    public function setActiveUser($activeUser)
    {   
        if(!Utils::checkForTrait($activeUser, 'SdsDoctrineExtensions\Behaviour\User')){
            throw new \Exception('$activeUser must exhibit the SdsDoctrineExtensions\Behaviour\User trait');
        }
        $this->activeUser = $activeUser;
    }   
    
    public function getActiveUser(){
        return $this->activeUser;
    }
}
