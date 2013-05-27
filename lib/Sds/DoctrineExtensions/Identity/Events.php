<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{

    /**
     * Called before roles change. Can be used to roll the roles change back
     */
    const preUpdateRoles = 'preUpdateRoles';

    /**
     * Called during roles change.
     */
    const onUpdateRoles = 'onUpdateRoles';

    /**
     * Called after roles change complete
     */
    const postUpdateRoles = 'postUpdateRoles';

    /**
     *
     */
    const updateRolesDenied = 'updateRolesDenied';

    /**
     * Called before roles change. Can be used to roll the roles change back
     */
    const preUpdateCredential = 'preUpdateCredential';

    /**
     * Called during roles change.
     */
    const onUpdateCredential = 'onUpdateCredential';

    /**
     * Called after roles change complete
     */
    const postUpdateCredential = 'postUpdateCredential';

    /**
     *
     */
    const updateCredentialDenied = 'updateCredentialDenied';
}