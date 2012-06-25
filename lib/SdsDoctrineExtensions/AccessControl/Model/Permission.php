<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsCommon\AccessControl\PermissionInterface;

/**
 * Implementation of SdsCommon\AccessControl\PermissionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @ODM\EmbeddedDocument
 */
class Permission implements PermissionInterface
{
    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly
    */
    protected $state;

    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly
    */
    protected $action;

    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly
    */
    protected $role;

    /**
     * {@inheritdoc}
     */
    public function getState() {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * {@inheritdoc}
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * {@inheritdoc}
     */
    public function __construct($role, $action, $state = null){
        $this->state = (string) $state;
        $this->role = (string) $role;
        $this->action = (string) $action;
    }
}
