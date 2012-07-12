<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\Behaviour;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Implements the Sds\Common\Freeze\FreezeableInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait FreezeableTrait {

    /**
     * @ODM\Field(type="boolean")
     * @Sds\FreezeField
     * @Sds\DoNotAccessControlUpdate
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