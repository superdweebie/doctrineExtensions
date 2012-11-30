<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * The main annotaion for defineing the behaviour of Dojo Input generation.
 * It may be used as a class or property annotation.
 * It behaviour is defined by the annotations it contains.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class DojoInput extends Annotation {

    const event = 'annotationDojoInput';

    public $base;

    public $params;
}