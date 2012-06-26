<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze;

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
    }

    protected function updateRequiredRoleAwareUser(){
        if ($this->accessControlFreeze ||
            $this->accessControlThaw
        ) {
            $this->setRequireRoleAwareUser(true);
        } else {
            $this->setRequireRoleAwareUser(false);
        }
    }
}
