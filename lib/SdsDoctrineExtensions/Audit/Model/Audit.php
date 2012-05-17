<?php

namespace SdsDoctrineExtensions\Audit\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

/** @ODM\EmbeddedDocument */
class Audit
{
    /** 
     * @ODM\Id(strategy="UUID") 
     */
    protected $id;

    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */        
    protected $oldValue;

    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */         
    protected $newValue;
    
    /**
    * @ODM\Field(type="timestamp")
    * @SDS_Readonly 
    */         
    protected $changedOn;
    
    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */         
    protected $changedBy;    
    
    public function getId() {
        return $this->id;
    }

    public function getOldValue() {
        return $this->oldValue;
    }

    public function setOldValue($oldValue) {
        $this->oldValue = $oldValue;
    }

    public function getNewValue() {
        return $this->newValue;
    }

    public function setNewValue($newValue) {
        $this->newValue = $newValue;
    }

    public function getChangedOn() {
        return $this->changedOn;
    }

    public function setChangedOn($changedOn) {
        $this->changedOn = $changedOn;
    }

    public function getChangedBy() {
        return $this->changedBy;
    }

    public function setChangedBy($changedBy) {
        $this->changedBy = $changedBy;
    }    
}
