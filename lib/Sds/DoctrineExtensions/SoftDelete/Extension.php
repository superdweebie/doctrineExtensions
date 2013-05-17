<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension
{

    protected $filters = [
        'softDelete' => 'Sds\DoctrineExtensions\SoftDelete\Filter\SoftDelete'
    ];

    protected $subscribers = [
        'subscriber.softdelete.mainsubscriber',
        'subscriber.softdelete.stampsubscriber',
        'subscriber.softdelete.annotationsubscriber',
        'subscriber.softdelete.softdeletesubscriber'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'softDeleter' => 'Sds\DoctrineExtensions\SoftDelete\SoftDeleter',
            'subscriber.softdelete.mainsubscriber' => 'Sds\DoctrineExtensions\SoftDelete\MainSubscriber',
            'subscriber.softdelete.stampsubscriber' => 'Sds\DoctrineExtensions\SoftDelete\StampSubscriber',
            'subscriber.softdelete.annotationsubscriber' => 'Sds\DoctrineExtensions\SoftDelete\AnnotationSubscriber',
            'subscriber.softdelete.softdeletesubscriber' => 'Sds\DoctrineExtensions\SoftDelete\AccessControl\SoftDeleteSubscriber'
        ]
    ];

    protected $dependencies = [
        'extension.annotation' => true,
        'extension.identity' => true
    ];
}
