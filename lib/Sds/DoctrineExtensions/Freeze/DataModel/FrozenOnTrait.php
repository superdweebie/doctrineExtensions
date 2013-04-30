<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\Freeze\FrozenOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait FrozenOnTrait {

    /**
     * @ODM\Timestamp
     * @Sds\Freeze\FrozenOn
     */
    protected $frozenOn;

    /**
     *
     * @return timestamp
     */
    public function getFrozenOn(){
        return $this->frozenOn;
    }
}
