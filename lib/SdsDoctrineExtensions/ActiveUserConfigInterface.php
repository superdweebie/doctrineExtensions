<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

use SdsCommon\ActiveUser\ActiveUserAwareInterface;

/**
 * Use on classes that must be aware of the active user.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
interface ActiveUserConfigInterface extends ActiveUserAwareInterface{

    /**
     * @return \SdsCommon\User\UserInterface
     */
    public function getActiveUser();
}
