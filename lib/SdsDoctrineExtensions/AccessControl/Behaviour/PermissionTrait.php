<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

/**
 * Implements SdsCommon\AccessControl\PermissionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait PermissionTrait
{
    /**
    * @ODM\String
    * @SDS_Readonly
    */
    protected $state;

    /**
    * @ODM\String
    * @SDS_Readonly
    */
    protected $action;

    /**
    * @ODM\String
    * @SDS_Readonly
    */
    protected $role;

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
     * @param string $role
     * @param string $action
     * @param string $state
     */
    public function __construct($role, $action, $state = null){
        $this->role = (string) $role;
        $this->action = (string) $action;
        $this->state = isset($state) ? (string) $state : null;        
    }
}

