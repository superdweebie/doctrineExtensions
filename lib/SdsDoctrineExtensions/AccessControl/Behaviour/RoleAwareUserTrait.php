<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\AccessControl\Model\Permission;
use SdsDoctrineExtensions\AccessControl\Model\Role;
use SdsCommon\AccessControl\RoleInterface;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait RoleAwareUserTrait {

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
