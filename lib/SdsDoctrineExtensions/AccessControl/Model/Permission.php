<?php

namespace SdsDoctrineExtensions\AccessControl\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsCommon\AccessControl\PermissionInterface;
use SdsCommon\AccessControl\RoleInterface;

/** @ODM\EmbeddedDocument */
class Permission implements PermissionInterface
{    
    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */        
    protected $state;
    
    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */         
    protected $action;

    /**
    * @ODM\EmbedOne(
    *   targetDocument="SdsDoctrineExtensions\AccessControl\Model\Role"
    * )
    */             
    protected $role;
    
    public function getState() {
        return $this->state;
    }

    public function getAction() {
        return $this->action;
    }

    public function getRole() {
        return $this->role;
    }
    
    public function __construct(RoleInterface $role, $action, $state = null){
        if(!$role instanceof Role){
            throw new \InvalidArgumentException();
        }
        $this->state = (string) $state;
        $this->role = $role;
        $this->action = (string) $action;
    }
}
