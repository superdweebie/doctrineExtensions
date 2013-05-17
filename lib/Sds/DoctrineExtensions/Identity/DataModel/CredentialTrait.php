<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Implementation of Sds\Common\Identity\CredentialInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait CredentialTrait {

    /**
     * @ODM\String
     * @Sds\Serializer\Ignore("ignore_when_serializing"))
     * @Sds\Validator\Required
     * @Sds\Validator\Credential
     * @Sds\Crypt\Hash
     * @Sds\Dojo\Input(mixins = {"Sds/Form/PasswordTextBox"})
     */
    protected $credential;

    /**
     * Returns encrypted credential
     *
     * @return string
     */
    public function getCredential() {
        return $this->credential;
    }

    /**
     *
     * @param string $plaintext
     */
    public function setCredential($plaintext) {
        $this->credential = (string) $plaintext;
        return $this;
    }
}
