<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait UpdatedOnTrait {
   
    /** 
     * @ODM\Field(type="timestamp") 
     */
    protected $updatedOn;

    public function setUpdatedOn($time){
        $this->updatedOn = $time;
    }    
    
    public function getUpdatedOn(){
        return $this->updatedOn;
    }
}
