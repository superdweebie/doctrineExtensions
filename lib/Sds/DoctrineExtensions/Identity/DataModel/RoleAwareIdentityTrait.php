<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait RoleAwareIdentityTrait {

    /**
     * @ODM\Collection
     * @Sds\Roles
     * @Sds\AccessControl(@Sds\AccessControl\Update(false))
     * @Sds\Validator\IdentifierArray
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
        $this->roles[] = (string) $role;
    }

    /**
     *
     * @param string $role
     */
    public function removeRole($role){
        if(($key = array_search((string)$role, $this->roles)) !== false)
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
        return in_array((string)$role, $this->roles);
    }
}
