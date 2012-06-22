<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

use SdsCommon\ActiveUser\ActiveUserAwareTrait;

/**
 * Use on classes that must be aware of the active user.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait ActiveUserConfigTrait {

    use ActiveUserAwareTrait;

    /**
     *
     * @return \SdsCommon\User\UserInterface
     */
    public function getActiveUser() {
        return $this->activeUser;
    }
}
