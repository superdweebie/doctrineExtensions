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
    
    public function addRole(Role $role = null, $name = null, $zone = null){
        if($role){
            $this->roles[] = $role;
            return;
        } else {
            $name = (string) $name;        
            $zone = (string) $zone;
            $role = new Role($name, $zone);
            $this->roles[] = $role;            
        }        
    }
    
    public function removeRole(Role $roleToRemove = null, $name = null, $zone = null){
        if($roleToRemove){
            foreach($this->roles as $index => $role){
                if($role === $roleToRemove){
                    unset($this->roles[$index]);
                    $this->roles = array_values($this->roles());               
                    return;
                }
            } 
        } else {
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
}
