<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Owner;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'subscriber.owner.mainsubscriber',
        'subscriber.owner.annotationsubscriber'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.owner.mainsubscriber' => 'Sds\DoctrineExtensions\Owner\MainSubscriber',
            'subscriber.owner.annotationsubscriber' => 'Sds\DoctrineExtensions\Owner\AnnotationSubscriber'
        ]
    ];

    protected $dependencies = [
        'extension.annotation' => true,
        'extension.identity' => true,
    ];
}
