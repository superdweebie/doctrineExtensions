<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\Freeze\FrozenByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait FrozenByTrait {

    /**
     * @ODM\String
     * @ODM\Index
     * @Sds\Freeze\FrozenBy
     * @Sds\Validator(class = "Sds\Validator\Identifier")
     */
    protected $frozenBy;

    /**
     *
     * @return string
     */
    public function getFrozenBy(){
        return $this->frozenBy;
    }
}
