<?php

namespace SdsDoctrineExtensions\AccessControl\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\AccessControl\Model\Permission,
    SdsDoctrineExtensions\Audit\Mapping\Annotation\Audit as SDS_Audit,
    SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly,
    SdsDoctrineExtensions\Common\Utils;

trait DocumentAccessControl {
    
    protected $accessControlActiveUserTrait = 'SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser';
    protected $accessControlUserAccessControlTrait = 'SdsDoctrineExtensions\AccessControl\Behaviour\UserAccessControl';
    
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
    
    public function addPermission(Permission $permission){
        $this->permissions[] = $permission;
    }
    
    public function getAllPermissions(){
        return $this->permissions;
    }
    
    public function getUserPermissions($user = null, $state = null){
        $user = $this->checkUserParam($user);
        $roles = $user->getRoles($this->zone);
        if(!isset($state)){
            $state = $this->state;
        }
        $return = [];
        foreach($this->permissions as $permission){
            if(in_array($permission->getRole(), $roles) &&
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
        foreach($permssions as $permission){
            if($permission->getAction() == $action){
                return true;
            }
        }
        return false;
    }
    
    protected function checkUserParam($user = null){
        if(!isset($user)){
            if(!Utils::checkForTrait($this, $this->accessControlActiveUserTrait)){
                throw new \Exception('Class must exhibit the '.$this->accessControlActiveUserTrait.' trait.');
            } 
            $user = $this->activeUser;
        }
        if(!Utils::checkForTrait($user, $this->accessControlUserAccessControlTrait)){
            throw new \Exception('User object must exhibit the '.$this->accessControlUserAccessControlTrait.' trait.');
        }
        return $user;        
    }
}
