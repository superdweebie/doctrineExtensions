<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\DoNotHardDelete\Event;

/**
 * Provides constants for event names used by the do not hard delete extension
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{
    private function __construct() {}

    /**
     * Fires if delete is attempted on a class with the doNotHardDelete annotation
     */
    const hardDeleteDenied = 'hardDeleteDenied';
}