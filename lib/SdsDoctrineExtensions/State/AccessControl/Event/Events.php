<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\State\AccessControl\Event;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{
    private function __construct() {}

    /**
     * Triggered when activeUser attempts to change state of a document they don't have permission
     * for
     */
    const stateChangeDenied = 'stateChangeDenied';
}