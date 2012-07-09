<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\Constant;

/**
 * Defines commonly used role constants
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Role {

    /**
     * Denotes a user that is not authenticated
     */
    const guest = 'guest';

    /**
     * A user who has been authenticated
     */
    const user = 'user';

    /**
     * An authenticated user who has elevated rights
     */
    const admin = 'admin';

    /**
     * An authenticated user who has the highest rights
     */
    const superAdmin = 'superAdmin';
}
