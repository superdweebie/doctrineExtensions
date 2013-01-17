<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\SoftDelete\SoftDeletedByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftDeletedByTrait {

    /**
     * @ODM\Field(type="string")
     * @ODM\Index
     * @Sds\Validator(class = "Sds\Validator\Identifier")
     */
    protected $softDeletedBy;

    /**
     *
     * @param string $name
     */
    public function setSoftDeletedBy($name){
        $this->softDeletedBy = (string) $name;
    }

    /**
     *
     * @return string
     */
    public function getSoftDeletedBy(){
        return $this->softDeletedBy;
    }
}
