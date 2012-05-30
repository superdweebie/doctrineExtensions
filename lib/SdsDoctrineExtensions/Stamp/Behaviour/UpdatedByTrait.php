<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Common\Utils;

trait UpdatedByTrait {
   
    /** 
     * @ODM\Field(type="string") 
     */
    protected $updatedBy;
    
    public function setUpdatedBy($username){  
        $this->updatedBy = (string) $username;                
    }
        
    public function getUpdatedBy(){
        return $this->updatedBy;
    }         
}

