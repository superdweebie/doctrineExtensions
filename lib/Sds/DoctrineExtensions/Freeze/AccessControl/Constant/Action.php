<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\AccessControl\Constant;

/**
 * Defines commonly used action constants
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Action {

    /**
     * Mark a resouce as frozen
     */
    const freeze = 'freeze';

    /**
     * Unmark a resouce as frozen
     */
    const thaw = 'thaw';

}
