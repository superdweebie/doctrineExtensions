<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\DataModel;

use Sds\Common\AccessControl\PermissionInterface;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Implements Sds\Common\AccessControl\AccessControlledInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait AccessControlledTrait{

    /**
     * @ODM\EmbedMany(
     *   targetDocument="Sds\DoctrineExtensions\AccessControl\DataModel\Permission"
     * )
     * @Sds\Audit
     */
    protected $permissions = [];

    /**
     * Set all permissions
     *
     * @param array $permissions An array of PermissionInterface objects
     */
    public function setPermissions(array $permissions){
        $this->permissions = $permissions;
    }

    /**
     * Add a permission to the permissions array
     *
     * @param PermissionInterface $permission
     */
    public function addPermission(PermissionInterface $permission){
        $this->permissions[] = $permission;
    }

    /**
     *
     * @param \Sds\Common\AccessControl\PermissionInterface $permission
     */
    public function removePermission(PermissionInterface $permission){
        if(($key = array_search($permission, $this->permissions)) !== false)
        {
            unset($this->permissions[$key]);
        }
    }

    /**
     * Get all permissions
     *
     * @return array
     */
    public function getPermissions(){
        return $this->permissions;
    }
}
