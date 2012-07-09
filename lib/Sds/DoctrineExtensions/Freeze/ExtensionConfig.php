<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Sds\DoctrineExtensions\AbstractExtensionConfig;
use Sds\DoctrineExtensions\AccessControl;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\ActiveUserAwareTrait;

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
     * Flag if the Freeze\Subscriber\Stamp should be registed to stamp
     * documents on Freeze and Thaw events
     *
     * @var boolean
     */
    protected $useFreezeStamps = false;

    /**
     *
     * @var boolean
     */
    protected $accessControlFreeze = false;

    /**
     *
     * @var boolean
     */
    protected $accessControlThaw = false;

    /**
     *
     * @return boolean
     */
    public function getUseFreezeStamps() {
        return $this->useFreezeStamps;
    }

    /**
     *
     * @param boolean $useFreezeStamps
     */
    public function setUseFreezeStamps($useFreezeStamps) {
        $this->useFreezeStamps = (boolean) $useFreezeStamps;
    }

    /**
     *
     * @return boolean
     */
    public function getAccessControlFreeze() {
        return $this->accessControlFreeze;
    }

    /**
     *
     * @param boolean $accessControlFreeze
     */
    public function setAccessControlFreeze($accessControlFreeze) {
        $this->accessControlFreeze = $accessControlFreeze;
        $this->updateRequiredRoleAwareUser();
        $this->updateDependencies();
    }

    /**
     *
     * @return boolean
     */
    public function getAccessControlThaw() {
        return $this->accessControlThaw;
    }

    /**
     *
     * @param boolean $accessControlThaw
     */
    public function setAccessControlThaw($accessControlThaw) {
        $this->accessControlThaw = $accessControlThaw;
        $this->updateRequiredRoleAwareUser();
        $this->updateDependencies();
    }

    /**
     *
     */
    protected function updateRequiredRoleAwareUser(){
        if ($this->accessControlFreeze ||
            $this->accessControlThaw
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
        if ($this->accessControlFreeze ||
            $this->accessControlThaw
        ) {
            $accessControlConfig = new AccessControl\ExtensionConfig();
            $accessControlConfig->setAccessControlCreate(false);
            $accessControlConfig->setAccessControlRead(false);
            $accessControlConfig->setAccessControlUpdate(false);
            $accessControlConfig->setAccessControlDelete(false);
            $this->addDependency(
                'Sds\DoctrineExtensions\AccessControl',
                $accessControlConfig
            );
        } else {
            $this->removeDependency('Sds\DoctrineExtensions\AccessControl');
        }
    }
}
