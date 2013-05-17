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
        'subscriber.owner.annotationsubscriber',
        'subscirber.owner.updateownersubscriber'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.owner.mainsubscriber' => 'Sds\DoctrineExtensions\Owner\MainSubscriber',
            'subscriber.owner.annotationsubscriber' => 'Sds\DoctrineExtensions\Owner\AnnotationSubscriber',
            'subscirber.owner.updateownersubscriber' => 'Sds\DoctrineExtensions\Owner\AccessControl\UpdateOwnerSubscriber'
        ]
    ];

    protected $dependencies = [
        'extension.annotation' => true,
        'extension.identity' => true,
    ];
}
