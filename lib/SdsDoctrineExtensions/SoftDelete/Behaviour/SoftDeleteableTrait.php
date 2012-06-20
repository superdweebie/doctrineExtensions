<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements the SdsCommon\SoftDelete\SoftDeleteableInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftDeleteableTrait {

    /**
     * @ODM\Field(type="boolean")
     * @SDS_SoftDelete
     */
    protected $softDeleted;

    /**
     * Check if object has been soft deleted
     *
     * @return boolean
     */
    public function getSoftDeleted(){
        return $this->softDeleted;
    }

    /**
     * Soft delete the object
     */
    public function softDelete() {
        $this->softDeleted = true;
    }

    /**
     * Restore the object
     */
    public function restore() {
        $this->softDeleted = false;
    }
}