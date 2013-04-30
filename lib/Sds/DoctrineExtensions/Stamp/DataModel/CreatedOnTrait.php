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
trait CreatedOnTrait {

    /**
     * @ODM\Timestamp
     * @Sds\Stamp\CreatedOn
     * @Sds\Readonly
     */
    protected $createdOn;

    /**
     *
     * @return timestamp
     */
    public function getCreatedOn(){
        return $this->createdOn;
    }
}
