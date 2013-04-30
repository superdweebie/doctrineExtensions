<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{

    /**
     * Triggered when identity attempts to create a document they don't have permission
     * for
     */
    const createDenied = 'createDenied';

    /**
     * Triggers when identity attempts to update a document they don't have permission
     * for
     */
    const updateDenied = 'updateDenied';

    /**
     * Triggers wehn identity attempts to delete a document they don't have permission
     * for
     */
    const deleteDenied = 'deleteDenied';
}