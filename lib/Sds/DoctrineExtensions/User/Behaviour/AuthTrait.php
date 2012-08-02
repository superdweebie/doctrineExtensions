<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\User\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Implementation of Sds\Common\Auth\AuthInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait AuthTrait {

    /**
     * @ODM\Field(type="string")
     * @Sds\DoNotSerialize
     * @Sds\UiHints(label = "Password")
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\Password")
     * @Sds\CryptHash
     */
    protected $password;

    /**
     *
     * @var boolean
     */
    protected $isGuest;

    /**
     *
     * @return boolean
     */
    public function getIsGuest() {
        return $this->isGuest;
    }

    /**
     *
     * @param boolean $isGuest
     */
    public function setIsGuest($isGuest) {
        $this->isGuest = (boolean) $isGuest;
    }

    /**
     * Returns encrypted password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     *
     * @param string $plaintext
     */
    public function setPassword($plaintext) {
        $this->password = (string) $plaintext;
    }
}
