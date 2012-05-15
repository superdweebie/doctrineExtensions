<?php

namespace SdsDoctrineExtensions\Behaviour\Stamped;

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
        if(!Utils::checkForTrait($this, 'SdsDoctrineExtensions\Behaviour\ActiveUser')){
            throw new \Exception('Class must exhibit the SdsDoctrineExtensions\Behaviour\AciveUser trait in order to use the CreatedBy trait.');
        }
        $this->createdBy = $this->activeUser->getUsername(); 
    }
        
    public function getCreatedBy(){
        return $this->createdBy;
    }      
}
