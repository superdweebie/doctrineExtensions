<?php

namespace SdsDoctrineExtensions\ActiveUser\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
use SdsCommon\User\UserInterface;

trait ActiveUserTrait {
   
    protected $activeUser;
    
    public function setActiveUser(UserInterface $activeUser)
    {           
        $this->activeUser = $activeUser;
    }   
    
    public function getActiveUser(){
        return $this->activeUser;
    }
}
