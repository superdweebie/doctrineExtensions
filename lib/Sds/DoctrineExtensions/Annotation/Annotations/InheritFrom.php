<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Must be used in the context of a Dojo class annotation
 * Defines an array of Dojo modules that the Dojo Model must inherit from
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 */
final class InheritFrom extends Annotation
{
    public $value = [];
}
