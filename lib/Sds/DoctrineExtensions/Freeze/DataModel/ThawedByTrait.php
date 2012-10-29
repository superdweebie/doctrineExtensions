<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\DataModel;

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
     * @Sds\Validator(class = "Sds\Common\Validator\IdentifierValidator")
     */
    protected $thawedBy;

    /**
     *
     * @param string $name
     */
    public function setThawedBy($name){
        $this->thawedBy = (string) $name;
    }

    /**
     *
     * @return string
     */
    public function getThawedBy(){
        return $this->thawedBy;
    }
}
