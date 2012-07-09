<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use Sds\DoctrineExtensions\SoftDelete\Mapping\Annotation\SoftDeleteField as SDS_SoftDeleteField;

/**
 * Implements the Sds\Common\SoftDelete\SoftDeleteableInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftDeleteableTrait {

    /**
     * @ODM\Field(type="boolean")
     * @SDS_SoftDeleteField
     * @SDS_DoNotAccessControlUpdate
     */
    protected $softDeleted = false;

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