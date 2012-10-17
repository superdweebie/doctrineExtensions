<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State;

use Sds\DoctrineExtensions\AbstractExtensionConfig;
use Sds\DoctrineExtensions\RolesExtensionConfigTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig {

    use RolesExtensionConfigTrait;
    
    /**
     * Should access control be applied to state changes?
     *
     * @var boolean
     */
    protected $enableAccessControl = false;

    /**
     *
     * @return boolean
     */
    public function getEnableAccessControl() {
        return $this->enableAccessControl;
    }

    /**
     *
     * @param boolean $enableAccessControl
     */
    public function setEnableAccessControl($enableAccessControl) {
        $this->enableAccessControl = (boolean) $enableAccessControl;
        $this->updateDependencies();
    }

    /**
     *
     */
    protected function updateDependencies(){
        if ($this->enableAccessControl) {
            $this->addDependency(
                'Sds\DoctrineExtensions\AccessControl'
            );
        } else {
            $this->removeDependency('Sds\DoctrineExtensions\AccessControl');
        }
    }
}
