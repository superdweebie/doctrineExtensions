<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\Behaviour;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Implements Sds\Common\AccessControl\PermissionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait PermissionTrait
{
    /**
     * @ODM\String
     * @ODM\Index
     * @Sds\Readonly
     * @Sds\UiHints(
     *     label = "State"
     * )
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
     */
    protected $state;

    /**
     * @ODM\String
     * @ODM\Index
     * @Sds\Readonly
     * @Sds\UiHints(
     *     label = "Action"
     * )
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
     */
    protected $action;

    /**
     * @ODM\String
     * @ODM\Index
     * @Sds\Readonly
     * @Sds\UiHints(
     *     label = "Role"
     * )
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
     */
    protected $role;

    /**
     * @ODM\Boolean
     */
    protected $stateEqualToParent;

    /**
     *
     * @param string $role
     * @param string $action
     * @param string $state
     */
    public function __construct($role, $action, $state = null){
        $this->role = (string) $role;
        $this->action = (string) $action;
        $this->state = isset($state) ? (string) $state : null;
    }

    /**
     *
     * @return string
     */
    public function getState() {
        return $this->state;
    }

    /**
     *
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     *
     * @return string
     */
    public function getRole() {
        return $this->role;
    }

    /**
     *
     * @param boolean $value
     */
    public function setStateEqualToParent($value){
        $this->stateEqualToParent = (boolean) $value;
    }

    /**
     *
     * @return boolean
     */
    public function getStateEqualToParent(){
        return $this->stateEqualToParent;
    }
}

