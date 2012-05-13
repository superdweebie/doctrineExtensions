<?php

namespace SdsDoctrineExtensions;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait Audit {
  
    /**
    * @ODM\Field(type="hash")
    */      
    protected $audits = array();  
    
    protected $oldValues;
    
    protected $propertiesToAudit;
    
    protected $timestampAudit = true;
    
    protected $userstampAudit = true;
    
    /** 
     * @ODM\PostLoad 
     */
    public function autoSetOldValues(){
       $this->oldValues = get_object_vars($this);
    }   
    
    public function getOldValues(){
        return $oldValues;
    }

    public function setPropertiesToAudit(array $propertiesToAudit){
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
    
    /** 
     * @ODM\PrePersist 
     */
    public function autoAddAudit(){
        $newValues = get_object_vars($this);
        $oldValues = $this->oldValues;
        foreach($this->propertiesToAudit as $propertyToAudit){
            if($oldValues[$propertyToAudit] != $newValues[$propertyToAudit]){
                $this->audits[] = $propertyToAudit;
            }
        }      
    }    
}
