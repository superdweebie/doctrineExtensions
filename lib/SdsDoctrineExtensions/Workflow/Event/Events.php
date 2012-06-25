<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Event\Workflow;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{
    private function __construct() {}

    /**
     * Called before state change. Can be used to roll the state change back
     */
    const preStateChange = 'preStateChange';

    /**
     * Called during state change. Can be used to update the workflow vars
     */
    const onStateChange = 'onStateChange';

    /**
     * Called after state change complete
     */
    const postStateChange = 'postStateChange';
}