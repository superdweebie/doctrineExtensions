<?php

namespace SdsDoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait SoftDelete {
  
    /** 
     * @ODM\Field(type="boolean") 
     */   
    protected $deleted;
    
    public function setDeleted($deleted){        
        $this->deleted = (boolean) $deleted;
    }
    
    public function getDeleted(){
        return $this->deleted;
    }
    
    public function delete(){
        $this->setDeleted(true);
    }
    
    public function restore(){
        $this->setDeleted(false);
    }
}