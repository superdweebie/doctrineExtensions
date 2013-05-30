<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp\DataModel;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait CreatedByTrait {

    /**
     * @ODM\String
     * @Sds\Stamp\CreatedBy
     */
    protected $createdBy;

    /**
     *
     * @return string
     */
    public function getCreatedBy(){
        return $this->createdBy;
    }
}
