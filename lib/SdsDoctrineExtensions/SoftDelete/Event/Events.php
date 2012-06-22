<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\Event;

/**
 * Provides constants for event names used by the soft delete extension
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{
    private function __construct() {}

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
    const preSoftRestore = 'preSoftRestore';

    /**
     * Fires after a soft deleted document is restored
     */
    const postSoftRestore = 'postSoftRestore';
}