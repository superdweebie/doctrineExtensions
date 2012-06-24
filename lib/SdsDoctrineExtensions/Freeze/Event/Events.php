<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze\Event;

/**
 * Provides constants for event names used by the freeze extension
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{
    private function __construct() {}

    /**
     * Fires before freeze happens
     */
    const preFreeze = 'preFreeze';

    /**
     * Fires after freeze happens
     */
    const postFreeze = 'postFreeze';

    /**
     * Fires before a frozen document is thawed
     */
    const preThaw = 'preThaw';

    /**
     * Fires after a frozen document is thawed
     */
    const postThaw = 'postThaw';
    
    /**
     * Fires if an updated is attempted on a frozen object
     */
    const frozenUpdateDenied = 'frozenUpdateDenied';
    
    /**
     * Fires if a delete is attempted on a frozen object
     */
    const frozenDeleteDenied = 'frozenDeleteDenied';    
}