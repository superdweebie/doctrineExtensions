<?php

namespace SdsDoctrineExtensions\AccessControl\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;    

/** @ODM\EmbeddedDocument */
class ZoneRole
{
    /** 
     * @ODM\Id(strategy="UUID") 
     */
    protected $id;

    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly 
    */            
    protected $zone;

    /**
    * @ODM\Field(type="hash")
    */          
    protected $roles = array();
    
    public function getId() {
        return $this->id;
    }

    public function getZone() {
        return $this->zone;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function setRoles(array $roles) {
        $this->roles = $roles;
    }
    
    public function addRole($role){
        $this->roles[] = (string) $role;
    }
    
    public function removeRole($role){
        $role = (string) $role;
        foreach($this->roles as $index => $item){
            if($item == $role){
                unset($this->roles[$index]);
                $this->roles = array_values($this->roles);
                return;
            }
        }        
    }
    
    public function __construct(array $roles = [], $zone = null){
        $this->roles = $roles;
        $this->zone = (string) $zone;
    }
}
