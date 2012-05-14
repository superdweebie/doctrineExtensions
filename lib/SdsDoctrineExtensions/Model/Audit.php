<?php

namespace SdsDoctrineExtensions\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Audit
{
    /** 
     * @ODM\Id(strategy="UUID") 
     */
    protected $id;

    /**
    * @ODM\Field(type="string")
    */        
    protected $oldValue;

    /**
    * @ODM\Field(type="string")
    */         
    protected $newValue;
    
    /**
    * @ODM\Field(type="timestamp")
    */         
    protected $changedOn;
    
    /**
    * @ODM\Field(type="string")
    */         
    protected $changedBy;    
    
    
}
