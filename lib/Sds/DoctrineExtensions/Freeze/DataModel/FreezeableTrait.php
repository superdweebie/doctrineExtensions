<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\DataModel;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Implements the Sds\Common\Freeze\FreezeableInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait FreezeableTrait {

    /**
     * @ODM\Boolean
     * @Sds\Freeze
     */
    protected $frozen = false;

}