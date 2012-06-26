<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\State;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements AnnotationReaderAwareInterface {

    use AnnotationReaderAwareTrait;

    /**
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
    }

    /**
     * 
     */
    protected function updateRequiredRoleAwareUser(){
        if ($this->$accessControlStateChange) {
            $this->setRequireRoleAwareUser(true);
        } else {
            $this->setRequireRoleAwareUser(false);
        }
    }
}
