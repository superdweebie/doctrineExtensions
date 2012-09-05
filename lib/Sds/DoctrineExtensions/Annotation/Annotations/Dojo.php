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
 * The main annotaion for defineing the behaviour of Dojo Model generation.
 * It may be used as a class or property annotation.
 * It behaviour is defined by the annotations it contains.
 *
 * The following annotations are permissiable in a class or property context
 *     ValidatorGroup
 *     Metadata
 *
 * The following annotaions are permissable only in a class context
 *     InheritFrom
 *     ClassName
 *     Discriminator
 *
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
final class Dojo extends Annotation {

    const event = 'annotationDojo';
}