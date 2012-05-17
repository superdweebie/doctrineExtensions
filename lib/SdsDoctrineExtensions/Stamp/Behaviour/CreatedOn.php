<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait CreatedOn {
   
    /** 
     * @ODM\Field(type="timestamp") 
     */
    protected $createdOn;

    /** 
     * @ODM\PrePersist 
     */
    public function autoSetCreatedOn(){
        $this->createdOn = time();
    }
    
    public function getCreatedOn(){
        return $this->createdOn;
    }
}
