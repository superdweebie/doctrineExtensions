<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsDoctrineExtensions\AccessControl;
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
     * Flag if the SoftDelete\Subscriber\Stamp should be registed to stamp
     * documents on softDelete and Restore events
     *
     * @var boolean
     */
    protected $useSoftDeleteStamps = false;

    /**
     *
     * @var boolean
     */
    protected $accessControlSoftDelete = false;

    /**
     *
     * @var boolean
     */
    protected $accessControlRestore = false;

    /**
     *
     * @return boolean
     */
    public function getUseSoftDeleteStamps() {
        return $this->useSoftDeleteStamps;
    }

    /**
     *
     * @param boolean $useSoftDeleteStamps
     */
    public function setUseSoftDeleteStamps($useSoftDeleteStamps) {
        $this->useSoftDeleteStamps = (boolean) $useSoftDeleteStamps;
    }

    /**
     *
     * @return boolean
     */
    public function getAccessControlSoftDelete() {
        return $this->accessControlSoftDelete;
    }

    /**
     *
     * @param boolean $accessControlSoftDelete
     */
    public function setAccessControlSoftDelete($accessControlSoftDelete) {
        $this->accessControlSoftDelete = $accessControlSoftDelete;
        $this->updateRequiredRoleAwareUser();
        $this->updateDependencies();
    }

    /**
     *
     * @return boolean
     */
    public function getAccessControlRestore() {
        return $this->accessControlRestore;
    }

    /**
     *
     * @param boolean $accessControlRestore
     */
    public function setAccessControlRestore($accessControlRestore) {
        $this->accessControlRestore = $accessControlRestore;
        $this->updateRequiredRoleAwareUser();
        $this->updateDependencies();
    }

    /**
     *
     */
    protected function updateRequiredRoleAwareUser(){
        if ($this->accessControlSoftDelete ||
            $this->accessControlRestore
        ) {
            $this->setRequireRoleAwareUser(true);
        } else {
            $this->setRequireRoleAwareUser(false);
        }
    }

    /**
     *
     */
    protected function updateDependencies(){
        if ($this->accessControlSoftDelete ||
            $this->accessControlRestore
        ) {
            $accessControlConfig = new AccessControl\ExtensionConfig();
            $accessControlConfig->setAccessControlCreate(false);
            $accessControlConfig->setAccessControlRead(false);
            $accessControlConfig->setAccessControlUpdate(false);
            $accessControlConfig->setAccessControlDelete(false);
            $this->addDependency(
                'SdsDoctrineExtensions\AccessControl',
                $accessControlConfig
            );
        } else {
            $this->removeDependency('SdsDoctrineExtensions\AccessControl');
        }
    }
}
