<?php

namespace SdsDoctrineExtensions\Audit\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsCommon\Audit\AuditInterface;

/** @ODM\EmbeddedDocument */
class Audit implements AuditInterface
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
    
    public function __construct($oldValue, $newValue, $changedOn, $changedBy){
        $this->oldValue = (string) $oldValue;
        $this->newValue = (string) $newValue;
        $this->changedOn = $changedOn;
        $this->changedBy = (string) $changedBy;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getOldValue() {
        return $this->oldValue;
    }

    public function getNewValue() {
        return $this->newValue;
    }

    public function getChangedOn() {
        return $this->changedOn;
    }

    public function getChangedBy() {
        return $this->changedBy;
    }
}
