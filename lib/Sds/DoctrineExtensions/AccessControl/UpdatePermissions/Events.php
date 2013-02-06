<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\UpdatePermissions;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{

    /**
     * Called before Permissions change. Can be used to roll the Permissions change back
     */
    const preUpdatePermissions = 'preUpdatePermissions';

    /**
     * Called during Permissions change.
     */
    const onUpdatePermissions = 'onUpdatePermissions';

    /**
     * Called after Permissions change complete
     */
    const postUpdatePermissions = 'postUpdatePermissions';

    /**
     *
     */
    const updatePermissionsDenied = 'updatePermissionsDenied';

}