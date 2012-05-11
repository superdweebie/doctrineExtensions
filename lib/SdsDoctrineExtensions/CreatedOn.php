<?php

namespace SdsDoctrineExtensions;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait CreatedOn {
   
    /** 
     * @ODM\Field(type="timestamp") 
     */
    protected $createdOn;

    /** 
     * @ODM\PrePersist 
     */
    public function autosetCreatedOn(){
        if (!isset($this->createdOn)){
            $this->createdOn = time();
        }
    }
    
    public function getCreatedOn(){
        return $this->createdOn;
    }
}
