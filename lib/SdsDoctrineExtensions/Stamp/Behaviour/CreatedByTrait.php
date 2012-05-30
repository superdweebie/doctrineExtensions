<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\User\UserInterface;

trait CreatedByTrait {
       
    /** 
     * @ODM\Field(type="string") 
     */
    protected $createdBy;
    
    public function setCreatedBy(UserInterface $user){
        $this->createdBy = (string) $user->getUsername(); 
    }
        
    public function getCreatedBy(){
        return $this->createdBy;
    }      
}
