<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Behaviour;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Implements the Sds\Common\SoftDelete\SoftDeleteableInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftDeleteableTrait {

    /**
     * @ODM\Field(type="boolean")
     * @Sds\SoftDeleteField
     * @Sds\DoNotAccessControlUpdate
     * )
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