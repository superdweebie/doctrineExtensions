<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsDoctrineExtensions\Audit\Mapping\Annotation\Audit as SDS_Audit;
use SdsCommon\AccessControl\PermissionInterface;

/**
 * Implements SdsCommon\AccessControl\AccessControlledInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait ControlledObjectTrait{

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

    /**
     * Set the current resource state
     *
     * @param string $state
     */
    public function setState($state){
        $this->state = (string) $state;
    }

    /**
     * @return string
     */
    public function getState(){
        return $this->state;
    }

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
     * @param \SdsCommon\AccessControl\PermissionInterface $permission
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
