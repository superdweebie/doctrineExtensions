<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Owner;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{

    /**
     * Called before owner change. Can be used to roll the change back
     */
    const preUpdateOwner = 'preUpdateOwner';

    /**
     * Called during owner change.
     */
    const onUpdateOwner = 'onUpdateOwner';

    /**
     * Called after owner change complete
     */
    const postUpdateOwner = 'postUpdateOwner';

    /**
     *
     */
    const updateOwnerDenied = 'updateOwnerDenied';

}