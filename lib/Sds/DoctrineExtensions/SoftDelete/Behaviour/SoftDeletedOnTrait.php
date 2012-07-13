<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\SoftDelete\SoftDeletedOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftDeletedOnTrait {

    /**
     * @ODM\Field(type="timestamp")
     * @Sds\UiHints(label = "Soft Deleted On")
     */
    protected $softDeletedOn;

    /**
     *
     * @param timestamp $timestamp
     */
    public function setSoftDeletedOn($timestamp){
        $this->softDeletedOn = $timestamp;
    }

    /**
     *
     * @return timestamp
     */
    public function getSoftDeletedOn(){
        return $this->softDeletedOn;
    }
}
