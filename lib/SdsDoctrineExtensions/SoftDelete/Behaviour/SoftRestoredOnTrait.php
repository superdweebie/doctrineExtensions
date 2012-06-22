<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \SdsCommon\SoftDelete\SoftRestoredOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftRestoredOnTrait {

    /**
     * @ODM\Field(type="timestamp")
     */
    protected $restoredOn;

    /**
     *
     * @param timestamp $timestamp
     */
    public function setSoftRestoredOn($timestamp){
        $this->restoredOn = $timestamp;
    }

    /**
     *
     * @return timestamp
     */
    public function getSoftRestoredOn(){
        return $this->restoredOn;
    }
}
