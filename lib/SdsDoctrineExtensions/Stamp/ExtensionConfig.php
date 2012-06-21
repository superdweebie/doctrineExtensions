<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Stamp;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsCommon\User\UserInterface;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig {

    /**
     *
     * @var \SdsCommon\User\UserInterface
     */
    protected $activeUser;

    /**
     *
     * @param \SdsCommon\User\UserInterface $user
     */
    public function __construct(UserInterface $activeUser){
        $this->activeUser= $activeUser;
    }

    /**
     *
     * @return \SdsCommon\User\UserInterface
     */
    public function getActiveUser() {
        return $this->activeUser;
    }
}
