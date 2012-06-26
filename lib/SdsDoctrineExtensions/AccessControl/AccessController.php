<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl;

use SdsCommon\AccessControl\AccessControllerInterface;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\User\RoleAwareUserInterface;

/**
 * Defines methods for a manager object to check permssions
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AccessController implements AccessControllerInterface{

    /**
     * {@inheritdoc}
     */
    static public function getAllowedActions(
        AccessControlledInterface $object,
        RoleAwareUserInterface $user,
        $state = null
    ){
        $state = isset($state) ? $state : $object->getState();
        $roles = $user->getRoles();

        $allowedActions = array();

        foreach ($object->getPermissions() as $permission){
            if ($permission->getState() == $state &&
                in_array($permission->getRole(), $roles)
            ) {
                $allowedActions[] = $permission->getAction();
            }
        }
        return $allowedActions;
    }

    /**
     * {@inheritdoc}
     */
    static public function isActionAllowed(
        AccessControlledInterface $object,
        $action,
        RoleAwareUserInterface $user,
        $state = null
    ){
        $state = isset($state) ? $state : $object->getState();
        $roles = $user->getRoles();

        foreach ($object->getPermissions() as $permission){
            if ($permission->getState() == $state &&
                $permission->getAction() == $action &&
                in_array($permission->getRole(), $roles)
            ) {
                return true;
            }
        }
        return false;
    }
}
