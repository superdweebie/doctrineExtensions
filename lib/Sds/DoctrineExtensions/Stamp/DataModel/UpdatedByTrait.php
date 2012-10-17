<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\Stamp\UpdatedByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait UpdatedByTrait {

    /**
     * @ODM\Field(type="string")
     */
    protected $updatedBy;

    /**
     *
     * @param string $name
     */
    public function setUpdatedBy($name){
        $this->updatedBy = (string) $name;
    }

    /**
     *
     * @return string
     */
    public function getUpdatedBy(){
        return $this->updatedBy;
    }
}

