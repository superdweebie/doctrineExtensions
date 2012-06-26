<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\User\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait RoleAwareUserTrait {

    use UserTrait;
    
    /**
     * @ODM\Field(type="hash")
     */
    protected $roles = [];

    /**
     *
     * @param array $roles
     */
    public function setRoles(array $roles){
        $this->roles = $roles;
    }

    /**
     *
     * @param string $role
     */
    public function addRole($role){
        $this->roles[] = $role;
    }

    /**
     *
     * @param string $role
     */
    public function removeRole($role){
        if(($key = array_search($role, $this->roles)) !== false)
        {
            unset($this->roles[$key]);
        }
    }

    /**
     *
     * @return array
     */
    public function getRoles(){
        return $this->roles;
    }

    /**
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole($role){
        return in_array($role, $this_roles);
    }
}
