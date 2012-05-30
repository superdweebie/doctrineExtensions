<?php

namespace SdsDoctrineExtensions\AccessControl\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\AccessControl\Model\Permission;
use SdsDoctrineExtensions\AccessControl\Model\Role;
use SdsDoctrineExtensions\Audit\Mapping\Annotation\Audit as SDS_Audit;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsCommon\AccessControl\PermissionInterface;
use SdsCommon\AccessControl\UserInterface;
use SdsCommon\User\ActiveUserInterface;

trait ControlledDocumentTrait {
        
    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */       
    protected $zone;
    
    /**
    * @ODM\Field(type="string")
    * @SDS_Audit 
    */       
    protected $state;
    
     /**
    * @ODM\EmbedMany(
    *   targetDocument="SdsDoctrineExtensions\AccessControl\Model\Permission"
    * )
    */     
    protected $permissions = [];
    
    public function setZone($zone){
        $this->zone = (string) $zone;
        foreach($this->permissions as $permission){
            $permission->getRole()->setZone($zone);
        }
    }
    
    public function getZone(){
        return $this->zone;
    }
    
    public function setState($state){
        $this->state = (string) $state;
    }
    
    public function getState(){
        return $this->state;
    }
    
    public function addPermission($permission){
        if(is_array($permission)){
            $state = $permission['state'];
            $action = $permission['action'];
            $roleName = $permission['role']['name'];
            $roleZone = $this->getZone();
            $this->permissions[] = new Permission(
                new Role($roleName, $roleZone),
                $action,
                $state
            ); 
            return;
        }
        if($permission instanceof PermissionInterface){
            $permission->getRole()->setZone($this->getZone());
            $this->permissions[] = $permission; 
            return;
        }
        throw new \InvalidArgumentException('addPermission method must take a permission config array or Permission object.');
    }
    
    public function addPermissions(array $permissions = array()){
        foreach ($permissions as $permission){
            $this->addPermission($permission);
        }
    }
    
    public function getPermissions(){
        return $this->permissions;
    }
    
    public function getUserPermissions($user = null, $state = null){
        $user = $this->checkUserParam($user);
        $roles = $user->getRolesInZone($this->zone);
        if(!isset($state)){
            $state = $this->state;
        }
        $return = [];
        foreach($this->permissions as $permission){
            if(isset($roles) && in_array($permission->getRole(), $roles) &&
                $permission->getState() == $state
            ){
                $return[] = $permission;
            }
        }
        return $return;
    }
    
    public function isActionAllowed($action, $state = null, $user = null){
        $permissions = $this->getUserPermissions($user, $state);
        if(!isset($permissions) || count($permissions) == 0){
            return false;
        }
        foreach($permissions as $permission){
            if($permission->getAction() == $action){
                return true;
            }
        }
        return false;
    }
    
    protected function checkUserParam(UserInterface $user = null){
        if(!isset($user)){
            if(!$this instanceof ActiveUserInterface)){
                throw new \Exception('Class must exhibit the ActiveUserInterface.');
            } 
            $user = $this->getActiveUser();
        }
        return $user;        
    }
}
