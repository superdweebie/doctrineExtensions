<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use SdsDoctrineExtensions\Freeze\Mapping\Annotation\FreezeField as SDS_FreezeField;

/**
 * Implements the SdsCommon\Freeze\FreezeableInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait FreezeableTrait {

    /**
     * @ODM\Field(type="boolean")
     * @SDS_FreezeField
     * @SDS_DoNotAccessControlUpdate
     */
    protected $frozen = false;

    /**
     * Check if object has been frozen
     *
     * @return boolean
     */
    public function getFrozen(){
        return $this->frozen;
    }

    /**
     * Freeze the object
     */
    public function freeze() {
        $this->frozen = true;
    }

    /**
     * Thaw the object
     */
    public function thaw() {
        $this->frozen = false;
    }
}