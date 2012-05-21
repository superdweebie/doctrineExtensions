<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Common\Utils;

trait CreatedBy {
   
    protected $createdByactiveUserTrait = 'SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser';
    
    /** 
     * @ODM\Field(type="string") 
     */
    protected $createdBy;
    
    public function setCreatedBy($username){
        $this->createdBy = (string) $username; 
    }
        
    public function getCreatedBy(){
        return $this->createdBy;
    }      
}
