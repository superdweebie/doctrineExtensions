<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DoNotHardDelete;

/**
 * Provides constants for event names used by the do not hard delete extension
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{
    /**
     * Fires if delete is attempted on a class with the doNotHardDelete annotation
     */
    const hardDeleteDenied = 'hardDeleteDenied';
}