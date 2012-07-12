<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete;

/**
 * Provides constants for event names used by the soft delete extension
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{

    /**
     * Fires before soft delete happens
     */
    const preSoftDelete = 'preSoftDelete';

    /**
     * Fires after soft delete happens
     */
    const postSoftDelete = 'postSoftDelete';

    /**
     * Fires before a soft deleted document is restored
     */
    const preRestore = 'preRestore';

    /**
     * Fires after a soft deleted document is restored
     */
    const postRestore = 'postRestore';

    /**
     * Fires if an updated is attempted on a soft deleted object
     */
    const softDeletedUpdateDenied = 'softDeletedUpdateDenied';
}