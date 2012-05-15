<?php

namespace SdsDoctrineExtensions\Behaviour\Stamped;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait UpdatedOn {
   
    /** 
     * @ODM\Field(type="timestamp") 
     */
    protected $updatedOn;

    /** 
     * @ODM\PreUpdate 
     */
    public function autoSetUpdatedOn(){
        $this->updatedOn = time();
    }    
    
    public function getUpdatedOn(){
        return $this->updatedOn;
    }
}
