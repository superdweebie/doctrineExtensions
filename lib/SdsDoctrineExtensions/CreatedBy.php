<?php

namespace SdsDoctrineExtensions;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait CreatedBy {
   
    /** 
     * @ODM\Field(type="string") 
     */
    protected $createdBy;

    protected $activeUser;
    
    /** 
     * @ODM\PrePersist 
     */
    public function autosetCreatedBy(){
        if (!isset($this->createdBy)){                                          
            $this->createdBy = $this->activeUser->getUsername();   
        }
    }
        
    public function getCreatedBy(){
        return $this->createdBy;
    }
    
    public function setActiveUser($activeUser)
    {     
        $traits = Utils::getAllTraits($activeUser);
        if(!isset($traits['SdsDoctrineExtensions\User'])){
            throw new \Exception('$activeUser must exhibit the SdsDoctrineExtensions\User trait');
        }
        $this->activeUser = $activeUser;
    }       
}
