<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\SoftDelete\RestoredByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait RestoredByTrait {

    /**
     * @ODM\Field(type="string")
     * @ODM\Index
     * @Sds\ValidatorGroup(@Sds\Validator(class = "Sds\Common\Validator\IdentifierValidator"))
     */
    protected $restoredBy;

    /**
     *
     * @param string $username
     */
    public function setRestoredBy($username){
        $this->restoredBy = (string) $username;
    }

    /**
     *
     * @return string
     */
    public function getRestoredBy(){
        return $this->restoredBy;
    }
}
