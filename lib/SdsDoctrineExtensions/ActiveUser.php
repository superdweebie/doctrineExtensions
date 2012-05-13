<?php

namespace SdsDoctrineExtensions;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait ActiveUser {
   
    protected $activeUser;
        
    public function setActiveUser($activeUser)
    {     
        $traits = Utils::getAllTraits($activeUser);
        if(!isset($traits['SdsDoctrineExtensions\User'])){
            throw new \Exception('$activeUser must exhibit the SdsDoctrineExtensions\User trait');
        }
        $this->activeUser = $activeUser;
    }   
    
    public function getActiveUser(){
        return $this->activeUser;
    }
}
