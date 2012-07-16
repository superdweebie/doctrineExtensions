<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DiscriminatorMap;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
interface DiscriminatorMapInterface{

    /**
     * @return array
     */
    public function getDiscriminatorMap();
}