<?php

namespace SdsDoctrineExtensions\AccessControl\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

/** @ODM\EmbeddedDocument */
class Permission
{
    const STATE_ACTIVE = 'active';
    const STATE_INACTIVE = 'inactive';
    
    const ROLE_GUEST = 'guest';
    const ROLE_AUTHENTICATED = 'authenticated';
    const ROLE_ADMIN = 'admin';
    
    const ACTION_CREATE = 'create';
    const ACTION_READ = 'read';
    const ACTION_UPDATE = 'update';    
    const ACTION_DELETE = 'delete';
        
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
    
    public function __construct(Role $role, $action, $state = null){
        $this->state = (string) $state;
        $this->role = (string) $role;
        $this->action = (string) $action;
    }
}
