<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\Freeze\ThawedOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait ThawedOnTrait {

    /**
     * @ODM\Field(type="timestamp")
     * @Sds\UiHints(label = "Thawed On")
     */
    protected $thawedOn;

    /**
     *
     * @param timestamp $timestamp
     */
    public function setThawedOn($timestamp){
        $this->thawedOn = $timestamp;
    }

    /**
     *
     * @return timestamp
     */
    public function getThawedOn(){
        return $this->thawedOn;
    }
}