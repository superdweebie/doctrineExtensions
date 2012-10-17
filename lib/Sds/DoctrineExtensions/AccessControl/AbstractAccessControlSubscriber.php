<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Doctrine\Common\EventSubscriber;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractAccessControlSubscriber implements EventSubscriber
{

    /**
     * The array of identity roles that permissions will be checked against
     *
     * @var array
     */
    protected $roles;

    /**
     *
     * @return array
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     *
     * @param array $roles
     */
    public function setRoles($roles) {
        $this->roles = $roles;
    }

    /**
     *
     * @param array $roles
     */
    public function __construct(array $roles = []) {
        $this->roles = $roles;
    }
}
