<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Sds\Common\Identity\IdentityInterface;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait IdentityNameExtensionConfigTrait {

    /**
     * Name used when stamps are enabled.
     * @var string
     */
    protected $identityName;

    /**
     *
     * @return string
     */
    public function getIdentityName() {
        if (isset($this->identityName)){
            return $this->identityName;
        }
        if ($this->identity instanceof IdentityInterface){
            return $this->identity->getName();
        }
        return null;
    }

    /**
     *
     * @param string $identityName
     */
    public function setIdentityName($identityName) {
        $this->identityName = (string) $identityName;
    }
}
