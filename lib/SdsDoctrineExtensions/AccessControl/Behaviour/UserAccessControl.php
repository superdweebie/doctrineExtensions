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
    
    public function addRole($name, $zone = null){
        $name = (string) $name;        
        $zone = (string) $zone;
        $role = new Role($name, $zone);
        $this->roles[$role];
    }
    
    public function removeRole($name, $zone = null){
        $name = (string) $name;        
        $zone = (string) $zone;       
        foreach($this->roles as $index => $role){
            if($role->getName() == $name && $role->getZone() == $zone){
                unset($this->roles[$index]);
                $this->roles = array_values($this->roles());               
                return;
            }
        }       
    }
    
    public function getAllRoles(){
        return $this->roles;        
    }
    
    public function getRoles($zone = null){
        $zone = (string) $zone;
        $return = [];
        foreach($this->roles as $index => $role){
            if($role->getZone() == $zone){
                $return[] = $role;
            }
        } 
        return $return;
    }    
}
