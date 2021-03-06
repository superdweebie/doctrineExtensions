<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\AccessControl;

/**
 * Defines commonly used action constants
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Actions {

    /**
     * Mark a resouce as deleted, but do not actually remove it
     */
    const softDelete = 'softDelete';

    /**
     * Unmark a resource as deleted
     */
    const restore = 'restore';
}
