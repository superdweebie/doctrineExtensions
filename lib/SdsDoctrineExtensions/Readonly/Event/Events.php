<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Readonly\Event;

/**
 * Provides constants for event names used by the readonly extension
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{
    /**
     * Event called just before a changed readonly field is restored to it's
     * original value
     */
    const preReadonlyRestore = 'preReadonlyRestore';

    /**
     * Event called just after a changed readonly field is restored to it's
     * original value
     */
    const postReadonlyRestore = 'postReadonlyRestore';
}