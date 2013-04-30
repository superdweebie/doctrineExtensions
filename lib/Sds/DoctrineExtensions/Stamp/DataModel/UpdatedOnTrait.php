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
 * Implements \Sds\Common\Stamp\UpdatedOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait UpdatedOnTrait {

    /**
     * @ODM\Timestamp
     * @Sds\Stamp\UpdatedOn
     */
    protected $updatedOn;

    /**
     *
     * @return timestamp
     */
    public function getUpdatedOn(){
        return $this->updatedOn;
    }
}
