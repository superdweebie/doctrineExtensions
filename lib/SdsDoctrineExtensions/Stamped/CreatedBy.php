<?php

namespace SdsDoctrineExtensions\Stamped;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Utils;

trait CreatedBy {
   
    /** 
     * @ODM\Field(type="string") 
     */
    protected $createdBy;
    
    /** 
     * @ODM\PrePersist 
     */
    public function autoSetCreatedBy(){
        if (!isset($this->createdBy)){  
            $traits = Utils::getAllTraits($this);
            if(!isset($traits['SdsDoctrineExtensions\ActiveUser'])){
                throw new \Exception('Class must exhibit the SdsDoctrineExtensions\AciveUser trait in order to use the CreatedBy trait.');
            } else {
                $this->createdBy = $this->activeUser->getUsername(); 
            }              
        }
    }
        
    public function getCreatedBy(){
        return $this->createdBy;
    }      
}
