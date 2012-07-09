<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\Stamp\CreatedOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait CreatedOnTrait {

    /**
     * @ODM\Field(type="timestamp")
     * @SDS_Readonly
     */
    protected $createdOn;

    /**
     *
     * @param timestamp $timestamp
     */
    public function setCreatedOn($timestamp){
        $this->createdOn = $timestamp;
    }

    /**
     *
     * @return timestamp
     */
    public function getCreatedOn(){
        return $this->createdOn;
    }
}
