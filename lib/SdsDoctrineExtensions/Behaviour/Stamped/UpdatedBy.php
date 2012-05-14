<?php

namespace SdsDoctrineExtensions\Behaviour\Stamped;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Utils;

trait UpdatedBy {
   
    /** 
     * @ODM\Field(type="string") 
     */
    protected $updatedBy;
    
    /** 
     * @ODM\PrePersist 
     */
    public function autoSetUpdatedBy(){  
        $traits = Utils::getAllTraits($this);
        if(!isset($traits['SdsDoctrineExtensions\Behaviour\ActiveUser'])){
            throw new \Exception('Class must exhibit the SdsDoctrineExtensions\Behaviour\AciveUser trait in order to use the UpdatedBy trait.');
        } else {
            $this->updatedBy = $this->activeUser->getUsername(); 
        }                    
    }
        
    public function getUpdatedBy(){
        return $this->updatedBy;
    }         
}

