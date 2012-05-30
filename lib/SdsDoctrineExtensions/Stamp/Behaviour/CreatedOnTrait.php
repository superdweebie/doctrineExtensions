<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait CreatedOnTrait {
   
    /** 
     * @ODM\Field(type="timestamp") 
     */
    protected $createdOn;

    public function setCreatedOn($timestamp){
        $this->createdOn = $timestamp;
    }
    
    public function getCreatedOn(){
        return $this->createdOn;
    }
}
