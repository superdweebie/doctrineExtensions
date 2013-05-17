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
        'subscriber.stamp.mainsubscriber',
        'subscriber.stamp.annotationsubscriber',
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.stamp.mainsubscriber' => 'Sds\DoctrineExtensions\Stamp\MainSubscriber',
            'subscriber.stamp.annotationsubscriber' => 'Sds\DoctrineExtensions\Stamp\AnnotationSubscriber',
        ]
    ];

    protected $dependencies = [
        'extension.annotation' => true,
        'extension.identity' => true,
    ];
}
