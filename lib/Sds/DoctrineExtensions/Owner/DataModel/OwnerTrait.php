<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Owner\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Implementation of Sds\Common\Owner\OwnerInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait OwnerTrait {

    /**
     * @ODM\String
     * @Sds\Owner
     * @Sds\Validator\Required
     * @Sds\Validator\Identifier
     */
    protected $owner;

    /**
     *
     * @return string
     */
    public function getOwner() {
        return $this->owner;
    }

    /**
     *
     * @param string $owner
     */
    public function setOwner($owner) {
        $this->owner = (string) $owner;
    }
}
