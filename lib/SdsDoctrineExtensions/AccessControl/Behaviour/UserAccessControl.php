<?php

namespace SdsDoctrineExtensions\AccessControl\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\AccessControl\Model\Permission,
    SdsDoctrineExtensions\AccessControl\Model\ZoneRole;

trait UserAccessControl {
  
    /**
    * @ODM\EmbedMany(
    *     targetDocument="SdsDoctrineExtensions\AccessControl\Model\ZoneRole"
    * )
    */     
    protected $zoneRoles = [];
    
    public function addRole($role, $zone = null){
        $zone = (string) $zone;
        $role = (string) $role;
        foreach($this->zoneRoles as $zoneRole){
            if($zoneRole->getZone() == $zone){
                $zoneRole->addRole($role);
                return;
            }
        }
        $zoneRole = new ZoneRole($zone);
        $zoneRole->addRole($role);
        $this->addZoneRole($zoneRole);
    }
    
    public function removeRole($role, $zone = null){
        $zone = (string) $zone;
        $role = (string) $role;
        foreach($this->zoneRoles as $zoneRole){
            if($zoneRole->getZone() == $zone){
                $zoneRole->removeRole($role);
                if(count($zoneRole->getRoles())==0){
                    $this->removeZoneRole($zoneRole);
                }
                return;
            }
        }       
    }
    
    public function getRoles($zone = null){
        $zone = (string) $zone;
        return $this->getZoneRole($zone)->getRoles();
    }
    
    public function addZoneRole(ZoneRole $zoneRole){
        $this->zoneRoles[] = $zoneRole;
    }
    
    public function removeZoneRole(ZoneRole $zoneRole){
        foreach($this->zoneRoles as $index => $item){
            if($item == $zoneRole){
                unset($this->zoneRoles[$index]);
                $this->zoneRoles = array_values($this->zoneRoles);
                return;
            }
        }
    }
    
    public function setZoneRoles(array $zoneRoles){
        $this->zoneRoles = $zoneRoles;
    }
    
    public function getZoneRoles(){
        return $this->zoneRoles;
    }
    
    public function getZoneRole($zone){
        $zone = (string) $zone;
        foreach($this->zoneRoles as $index => $item){
            if($item == $zoneRole){
                return $zoneRole;
            }
        }        
    }
}
