<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\Freeze\ThawedByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait ThawedByTrait {

    /**
     * @ODM\Field(type="string")
     * @ODM\Index
     * @Sds\ValidatorGroup(@Sds\Validator(class = "Sds\Common\Validator\IdentifierValidator"))
     */
    protected $thawedBy;

    /**
     *
     * @param string $username
     */
    public function setThawedBy($username){
        $this->thawedBy = (string) $username;
    }

    /**
     *
     * @return string
     */
    public function getThawedBy(){
        return $this->thawedBy;
    }
}
