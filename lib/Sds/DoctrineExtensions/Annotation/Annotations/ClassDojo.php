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
 * @Annotation
 * @Target({"CLASS"})
 */
final class ClassDojo extends Annotation {

    const event = 'annotationClassDojo';

    const metadataKey = 'classDojo';

    public $inheritFrom;
    
    public $className;

    public $discriminator;

    public $validators;
}