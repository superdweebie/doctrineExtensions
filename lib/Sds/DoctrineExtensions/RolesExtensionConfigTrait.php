<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Sds\Common\AccessControl\AccessControlIdentityInterface;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait RolesExtensionConfigTrait {

    /**
     * Array of roles used for access control, if enabled.
     * @var array
     */
    protected $roles;

    /**
     *
     * @return array
     */
    public function getRoles() {
        if (isset($this->roles)){
            return $this->roles;
        }
        if ($this->identity instanceof AccessControlIdentityInterface){
            return $this->identity->getRoles();
        }
        return [];
    }

    /**
     *
     * @param array $roles
     */
    public function setRoles(array $roles) {
        $this->roles = $roles;
    }
}
