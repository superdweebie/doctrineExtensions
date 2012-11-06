<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * May be used in two contexts:
 *
 *     Serializer class annotation
 *         If true, the serializer will add the class name field to serialization
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 */
final class ClassName extends Annotation
{

    public $value = true;
}