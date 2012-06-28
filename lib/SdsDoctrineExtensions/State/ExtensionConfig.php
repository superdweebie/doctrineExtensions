<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\State;

use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;
use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsDoctrineExtensions\AccessControl;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig
implements
    AnnotationReaderAwareInterface,
    ActiveUserAwareInterface
{

    use AnnotationReaderAwareTrait;
    use ActiveUserAwareTrait;

    /**
     * Should access control be applied to state changes?
     *
     * @var boolean
     */
    protected $accessControlStateChange = false;

    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'SdsDoctrineExtensions\Audit' => null
    );

    /**
     *
     * @return boolean
     */
    public function getAccessControlStateChange() {
        return $this->accessControlStateChange;
    }

    /**
     *
     * @param boolean $accessControlStateChange
     */
    public function setAccessControlStateChange($accessControlStateChange) {
        $this->accessControlStateChange = (boolean) $accessControlStateChange;
        $this->updateRequiredRoleAwareUser();
        $this->updateDependencies();
    }

    /**
     *
     */
    protected function updateRequiredRoleAwareUser(){
        if ($this->accessControlStateChange) {
            $this->setRequireRoleAwareUser(true);
        } else {
            $this->setRequireRoleAwareUser(false);
        }
    }

    /**
     *
     */
    protected function updateDependencies(){
        if ($this->accessControlStateChange) {
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
