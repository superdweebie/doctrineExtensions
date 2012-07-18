<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait ClassNamePropertyTrait {

    /**
     *
     * @var string
     */
    protected $classNameProperty = 'className';

    /**
     *
     * @return string
     */
    public function getClassNameProperty() {
        return $this->classNameProperty;
    }

    /**
     *
     * @param string $classNameProperty
     */
    public function setClassNameProperty($classNameProperty) {
        $this->classNameProperty = $classNameProperty;
    }
}
