<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\User\UserInterface;

trait UpdatedByTrait {
   
    /** 
     * @ODM\Field(type="string") 
     */
    protected $updatedBy;
    
    public function setUpdatedBy(UserInterface $username){  
        $this->updatedBy = (string) $user->getUsername();               
    }
        
    public function getUpdatedBy(){
        return $this->updatedBy;
    }         
}

