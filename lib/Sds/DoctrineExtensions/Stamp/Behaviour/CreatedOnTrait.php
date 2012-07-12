<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp\Behaviour;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Implements \Sds\Common\Stamp\CreatedOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait CreatedOnTrait {

    /**
     * @ODM\Field(type="timestamp")
     * @Sds\Readonly
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
