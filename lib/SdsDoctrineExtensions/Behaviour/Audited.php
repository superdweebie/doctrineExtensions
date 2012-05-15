<?php

namespace SdsDoctrineExtensions\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait Audited {
  
    /**
    * @ODM\Field(type="hash")
    */      
    protected $audits = [];  
       
    protected $propertiesToAudit = [];
                
    protected function setPropertiesToAudit(array $propertiesToAudit){
        $properties = get_object_vars($this);
        foreach($propertiesToAudit as $propertyToAudit){
            if(!(array_key_exists($propertyToAudit, $properties))){
                throw new \Exception($proertyToAudit.' is not a property of '.get_class($this).', so cannot be audited.');
            }
        }
    }
    
    public function getPropertiesToAudit(){
        return $this->propertiesToAudit();
    }      
}
