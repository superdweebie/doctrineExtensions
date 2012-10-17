<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete;

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
     * Flag if the SoftDelete\Subscriber\Stamp should be registed to stamp
     * documents on softDelete and Restore events
     *
     * @var boolean
     */
    protected $enableSoftDeleteStamps = false;

    /**
     *
     * @var boolean
     */
    protected $enableAccessControl = false;

    /**
     *
     * @return boolean
     */
    public function getEnableSoftDeleteStamps() {
        return $this->enableSoftDeleteStamps;
    }

    /**
     *
     * @param boolean $enableSoftDeleteStamps
     */
    public function setEnableSoftDeleteStamps($enableSoftDeleteStamps) {
        $this->enableSoftDeleteStamps = (boolean) $enableSoftDeleteStamps;
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
