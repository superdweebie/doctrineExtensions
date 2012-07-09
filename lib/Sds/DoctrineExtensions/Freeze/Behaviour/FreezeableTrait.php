<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use Sds\DoctrineExtensions\Freeze\Mapping\Annotation\FreezeField as SDS_FreezeField;

/**
 * Implements the Sds\Common\Freeze\FreezeableInterface
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