<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\SoftDelete\RestoredOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait RestoredOnTrait {

    /**
     * @ODM\Field(type="timestamp")
     */
    protected $restoredOn;

    /**
     *
     * @param timestamp $timestamp
     */
    public function setRestoredOn($timestamp){
        $this->restoredOn = $timestamp;
    }

    /**
     *
     * @return timestamp
     */
    public function getRestoredOn(){
        return $this->restoredOn;
    }
}
