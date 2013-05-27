<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension
{

    protected $subscribers = [
        'subscriber.identity.mainsubscriber',
        'subscriber.identity.annotationsubscriber',
        'subscriber.identity.updateRolesSubscriber',
        'subscriber.identity.updateCredentialSubscriber'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.identity.mainsubscriber' => 'Sds\DoctrineExtensions\Identity\MainSubscriber',
            'subscriber.identity.annotationsubscriber' => 'Sds\DoctrineExtensions\Identity\AnnotationSubscriber',
            'subscriber.identity.updateRolesSubscriber' => 'Sds\DoctrineExtensions\Identity\AccessControl\UpdateRolesSubscriber',
            'subscriber.identity.updateCredentialSubscriber' => 'Sds\DoctrineExtensions\Identity\AccessControl\UpdateCredentialSubscriber',
        ]
    ];

    protected $dependencies = [
        'extension.annotation' => true,
    ];
}
