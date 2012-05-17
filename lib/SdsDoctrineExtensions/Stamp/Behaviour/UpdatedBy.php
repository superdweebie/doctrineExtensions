<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Common\Utils;

trait UpdatedBy {

    protected $updatedByactiveUserTrait = 'SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser';
    
    /** 
     * @ODM\Field(type="string") 
     */
    protected $updatedBy;
    
    /** 
     * @ODM\PreUpdate 
     */
    public function autoSetUpdatedBy(){  
        if(!Utils::checkForTrait($this, $this->updatedByactiveUserTrait)){
            throw new \Exception('Class must exhibit the '.$this->updatedByactiveUserTrait.' trait in order to use the UpdatedBy trait.');
        }
        $this->updatedBy = $this->activeUser->getUsername();                
    }
        
    public function getUpdatedBy(){
        return $this->updatedBy;
    }         
}

