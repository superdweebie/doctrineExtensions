<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{

    /**
     * Called before state change. Can be used to roll the state change back
     */
    const preTransition = 'preTransition';

    /**
     * Called during state change. Can be used to update the workflow vars
     */
    const onTransition = 'onTransition';

    /**
     * Called after state change complete
     */
    const postTransition = 'postTransition';
}