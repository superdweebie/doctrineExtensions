<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Sds\DoctrineExtensions\Serializer\Serializer as Constants;

/**
 * Mark a field to be skipped during serialization. Must be used in the context
 * of the Serializer annotation
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 */
final class Ignore extends Annotation
{

    public $value = Constants::IGNORE_UP_AND_DOWN;
}