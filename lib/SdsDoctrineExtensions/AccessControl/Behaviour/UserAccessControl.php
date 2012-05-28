<?php

namespace SdsDoctrineExtensions\AccessControl\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\AccessControl\Model\Permission,
    SdsDoctrineExtensions\AccessControl\Model\Role;

trait UserAccessControl {
  
    /**
    * @ODM\EmbedMany(
    *     targetDocument="SdsDoctrineExtensions\AccessControl\Model\Role"
    * )
    */     
    protected $roles = [];
    
    public function addRoles(array $roles = []){
        foreach($roles as $role){
            $this->addRole($role);
        }
    }
    
    public function addRole($role){
        if(is_array($role)){
            $name = (string) $role['name'];        
            if(isset($role['zone'])){
                $zone = (string) $role['zone'];
            } else {
                $zone = null;
            }
            $role = new Role($name, $zone);
        }
        if(!$role instanceof Role){        
            throw new \InvalidArgumentException('addRole method must take a permission config array or Role object.');         
        }   
        $this->roles[] = $role;        
        return;            
    }
    
    public function removeRole($role){
        if(is_array($role)){
            $name = (string) $role['name'];        
            $zone = (string) $role['zone'];                  
        }
        if($role instanceof $role){
            $name = $role->getName();
            $zone = $role->getZone();            
        }
        foreach($this->roles as $index => $userRole){
            if($userRole->getName() == $name && $userRole->getZone() == $zone){
                unset($this->roles[$index]);
                $this->roles = array_values($this->roles());               
                return;
            }
        }                 
    }
    
    public function getRoles(){
        return $this->roles;        
    }
    
    public function getRolesInZone($zone = null){
        $zone = (string) $zone;
        $return = [];
        foreach($this->roles as $index => $role){
            if($role->getZone() == $zone){
                $return[] = $role;
            }
        } 
        return $return;
    }
    
    public function hasRole($role){
        if(is_array($role)){
            $name = (string) $role['name'];        
            $zone = (string) $role['zone'];            
        }
        if($role instanceof Role){
            $name = $role->getName();
            $zone = $role->getZone();
        }
        foreach($this->roles as $userRole){
            if($userRole->getName() == $name && $userRole->getZone() == $zone){
                return true;
            }
        }
        return false;
    }
}
