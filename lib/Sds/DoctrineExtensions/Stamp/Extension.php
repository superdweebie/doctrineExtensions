<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'Sds\DoctrineExtensions\Stamp\MainSubscriber',
        'Sds\DoctrineExtensions\Stamp\AnnotationSubscriber',
    ];

    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true,
        'Sds\DoctrineExtensions\Identity' => true,
    ];
}
