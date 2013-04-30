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
     * @ODM\String
     * @ODM\Index
     * @Sds\Freeze\ThawedBy
     * @Sds\Validator(class = "Sds\Validator\Identifier")
     */
    protected $thawedBy;

    /**
     *
     * @return string
     */
    public function getThawedBy(){
        return $this->thawedBy;
    }
}
