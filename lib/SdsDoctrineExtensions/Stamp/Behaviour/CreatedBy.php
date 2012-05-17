<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Common\Utils;

trait CreatedBy {
   
    protected $createdByactiveUserTrait = 'SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser';
    
    /** 
     * @ODM\Field(type="string") 
     */
    protected $createdBy;
    
    /** 
     * @ODM\PrePersist 
     */
    public function autoSetCreatedBy(){
        if(!Utils::checkForTrait($this, $this->createdByactiveUserTrait)){
            throw new \Exception('Class must exhibit the '.$this->createdByactiveUserTrait.' trait in order to use the CreatedBy trait.');
        }
        $this->createdBy = $this->activeUser->getUsername(); 
    }
        
    public function getCreatedBy(){
        return $this->createdBy;
    }      
}
