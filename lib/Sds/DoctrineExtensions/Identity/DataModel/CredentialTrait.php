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
     * @Sds\Serializer(@Sds\Ignore)
     * @Sds\Dojo(
     *     @Sds\Metadata({
     *         "type" = "password",
     *         "label" = "Password:"
     *     }),
     *     @Sds\ValidatorGroup(
     *         @Sds\Required,
     *         @Sds\Validator(class = "Sds/Common/Validator/CredentialValidator")
     *     )
     * )
     * @Sds\ValidatorGroup(
     *     @Sds\Required,
     *     @Sds\Validator(class = "Sds\Common\Validator\CredentialValidator")
     * )
     * @Sds\CryptHash
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
    }
}
