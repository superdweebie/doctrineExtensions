<?php

namespace SdsDoctrineExtensions\AccessControl\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;    
use SdsCommon\AccessControl\RoleInterface;

/** @ODM\EmbeddedDocument */
class Role implements RoleInterface
{

    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */            
    protected $name;
    
    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */            
    protected $zone;
    
    public function getName() {
        return $this->name;
    }
    
    public function getZone() {
        return $this->zone;
    }
    
    public function __construct($name, $zone = null){
        $this->name = (string) $name;
        $this->zone = (string) $zone;
    }
}
