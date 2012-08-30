<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\Freeze\FrozenOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait FrozenOnTrait {

    /**
     * @ODM\Field(type="timestamp")
     */
    protected $frozenOn;

    /**
     *
     * @param timestamp $timestamp
     */
    public function setFrozenOn($timestamp){
        $this->frozenOn = $timestamp;
    }

    /**
     *
     * @return timestamp
     */
    public function getFrozenOn(){
        return $this->frozenOn;
    }
}
