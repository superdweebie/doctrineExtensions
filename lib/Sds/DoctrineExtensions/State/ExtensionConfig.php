<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State;

use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\ActiveUserAwareTrait;
use Sds\DoctrineExtensions\AbstractExtensionConfig;
use Sds\DoctrineExtensions\AccessControl;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;

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
                'Sds\DoctrineExtensions\AccessControl',
                $accessControlConfig
            );
        } else {
            $this->removeDependency('Sds\DoctrineExtensions\AccessControl');
        }
    }
}
