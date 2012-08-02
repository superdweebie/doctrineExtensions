<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\Freeze\FrozenByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait FrozenByTrait {

    /**
     * @ODM\Field(type="string")
     * @ODM\Index
     * @Sds\UiHints(label = "Frozen by")
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
     */
    protected $frozenBy;

    /**
     *
     * @param string $username
     */
    public function setFrozenBy($username){
        $this->frozenBy = (string) $username;
    }

    /**
     *
     * @return string
     */
    public function getFrozenBy(){
        return $this->frozenBy;
    }
}