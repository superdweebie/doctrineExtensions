<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\Freeze\ThawedOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait ThawedOnTrait {

    /**
     * @ODM\Timestamp
     * @Sds\Freeze\ThawedOn
     */
    protected $thawedOn;

    /**
     *
     * @return timestamp
     */
    public function getThawedOn(){
        return $this->thawedOn;
    }
}
