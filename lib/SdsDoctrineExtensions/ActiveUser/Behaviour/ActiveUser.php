<?php

namespace SdsDoctrineExtensions\ActiveUser\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Common\Utils;

trait ActiveUser {
   
    protected $activeUser;
    protected $userTrait = 'SdsDoctrineExtensions\User\Behaviour\User';
    
    public function setActiveUser($activeUser)
    {   
        if(!Utils::checkForTrait($activeUser, $this->userTrait)){
            throw new \Exception('$activeUser must exhibit the '.$this->userTrait.' trait');
        }
        $this->activeUser = $activeUser;
    }   
    
    public function getActiveUser(){
        return $this->activeUser;
    }
}
