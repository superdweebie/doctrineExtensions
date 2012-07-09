<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Auth\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\Auth\Crypt;
use Sds\DoctrineExtensions\Serializer\Mapping\Annotation\DoNotSerialize as SDS_DoNotSerialize;

/**
 * Implementation of Sds\Common\Auth\AuthInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait AuthTrait {

    /**
     * @ODM\Field(type="string")
     * @SDS_DoNotSerialize
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
        $this->isGuest = $isGuest;
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
        $this->password = Crypt::encrypt($plaintext, Crypt::generateSalt(), Crypt::generateSalt());
    }
}
