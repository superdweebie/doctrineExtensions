<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait UpdatedByTrait {

    /**
     * @ODM\String
     * @Sds\Stamp\UpdatedBy
     */
    protected $updatedBy;

    /**
     *
     * @return string
     */
    public function getUpdatedBy(){
        return $this->updatedBy;
    }
}

