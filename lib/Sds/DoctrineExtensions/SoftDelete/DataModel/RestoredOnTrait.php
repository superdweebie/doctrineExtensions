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
trait RestoredOnTrait {

    /**
     * @ODM\Timestamp
     * @Sds\SoftDelete\RestoredOn
     */
    protected $restoredOn;

    /**
     *
     * @return timestamp
     */
    public function getRestoredOn(){
        return $this->restoredOn;
    }
}
