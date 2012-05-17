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
     * @ODM\Id(strategy="UUID") 
     */
    protected $id;

    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */        
    protected $state;

    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly
    */         
    protected $role;
    
    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */         
    protected $action;

    public function getId() {
        return $this->id;
    }

    public function getState() {
        return $this->state;
    }

    public function getRole() {
        return $this->role;
    }

    public function getAction() {
        return $this->action;
    }

    public function __construct($role, $action, $state = null){
        $this->state = (string) $state;
        $this->role = (string) $role;
        $this->action = (string) $action;
    }
}
