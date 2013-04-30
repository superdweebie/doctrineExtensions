<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait RestoredByTrait {

    /**
     * @ODM\String
     * @ODM\Index
     * @Sds\SoftDelete\RestoredBy
     * @Sds\Validator(class = "Sds\Validator\Identifier")
     */
    protected $restoredBy;

    /**
     *
     * @return string
     */
    public function getRestoredBy(){
        return $this->restoredBy;
    }
}
