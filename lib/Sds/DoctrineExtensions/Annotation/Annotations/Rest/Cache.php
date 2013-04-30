<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations\Rest;

use Doctrine\Common\Annotations\Annotation;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS"})
 */
final class Cache extends Annotation
{
    const event = 'annotationRestCache';

    public $public = false;

    public $private = false;

    public $noCache = false;
    
    public $maxAge = false;
}