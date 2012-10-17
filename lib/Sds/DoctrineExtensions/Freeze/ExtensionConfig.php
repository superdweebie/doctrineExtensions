<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Sds\DoctrineExtensions\AbstractExtensionConfig;
use Sds\DoctrineExtensions\IdentityNameExtensionConfigTrait;
use Sds\DoctrineExtensions\RolesExtensionConfigTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig
{

    use IdentityNameExtensionConfigTrait;
    use RolesExtensionConfigTrait;

    /**
     * Flag if the Freeze\StampSubscriber should be registed to stamp
     * documents on Freeze and Thaw events
     *
     * @var boolean
     */
    protected $enableFreezeStamps = false;

    /**
     * Should access control be applied to freeze and thaw?
     *
     * @var boolean
     */
    protected $enableAccessControl = false;

    /**
     *
     * @return boolean
     */
    public function getEnableFreezeStamps() {
        return $this->enableFreezeStamps;
    }

    /**
     *
     * @param boolean $enableFreezeStamps
     */
    public function setEnableFreezeStamps($enableFreezeStamps) {
        $this->enableFreezeStamps = (boolean) $enableFreezeStamps;
    }

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
