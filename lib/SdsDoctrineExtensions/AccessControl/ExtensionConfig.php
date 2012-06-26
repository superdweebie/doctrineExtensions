<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements
    AnnotationReaderAwareInterface,
    ActiveUserAwareInterface
{

    use AnnotationReaderAwareTrait;
    use ActiveUserAwareTrait;

    /**
     * Defines the permission class to use
     *
     * @var boolean
     */
    protected $permissionClass = 'SdsDoctrineExtensions\AccessControl\Model\Permission';
    
    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'SdsDoctrineExtensions\State' => null,
        'SdsDoctrineExtensions\Audit' => null,
        'SdsDoctrineExtensions\Readonly' => null
    );
    
    /**
     *
     * @var boolean
     */
    protected $accessControlCreate = true;

    /**
     *
     * @var boolean
     */
    protected $accessControlRead = true;

    /**
     *
     * @var boolean
     */
    protected $accessControlUpdate = true;

    /**
     *
     * @var boolean
     */
    protected $accessControlDelete = true;

    /**
     *
     */
    public function __create(){
        $this->setRequireRoleAwareUser(true);
    }

    /**
     *
     * @return boolean
     */
    public function getAccessControlCreate() {
        return $this->accessControlCreate;
    }

    /**
     *
     * @param boolean $accessControlCreate
     */
    public function setAccessControlCreate($accessControlCreate) {
        $this->accessControlCreate = (boolean) $accessControlCreate;
    }

    /**
     *
     * @return boolean
     */
    public function getAccessControlRead() {
        return $this->accessControlRead;
    }

    /**
     *
     * @param boolean $accessControlRead
     */
    public function setAccessControlRead($accessControlRead) {
        $this->accessControlRead = (boolean) $accessControlRead;
    }

    /**
     *
     * @return boolean
     */
    public function getAccessControlUpdate() {
        return $this->accessControlUpdate;
    }

    /**
     *
     * @param boolean $accessControlUpdate
     */
    public function setAccessControlUpdate($accessControlUpdate) {
        $this->accessControlUpdate = (boolean) $accessControlUpdate;
    }

    /**
     *
     * @return boolean
     */
    public function getAccessControlDelete() {
        return $this->accessControlDelete;
    }

    /**
     *
     * @param boolean $accessControlDelete
     */
    public function setAccessControlDelete($accessControlDelete) {
        $this->accessControlDelete = (boolean) $accessControlDelete;
    }
    
    /**
     * 
     * @return string
     */
    public function getPermissionClass() {
        return $this->permissionClass;
    }

    /**
     * 
     * @param string $permissonClass
     */
    public function setPermissionClass($permissionClass) {
        $this->permissionClass = (string) $permissionClass;
    }

    
}
