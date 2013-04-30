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

    protected $subscribers = [
        'Sds\DoctrineExtensions\SoftDelete\MainSubscriber',
        'Sds\DoctrineExtensions\SoftDelete\StampSubscriber',
        'Sds\DoctrineExtensions\SoftDelete\AnnotationSubscriber',
        'Sds\DoctrineExtensions\SoftDelete\AccessControl\SoftDeleteSubscriber'
    ];

    protected $filters = [
        'softDelete' => 'Sds\DoctrineExtensions\SoftDelete\Filter\SoftDelete'
    ];

    protected $defaultServiceManagerConfig = [
        'invokables' => [
            'softDeleter' => 'Sds\DoctrineExtensions\SoftDelete\SoftDeleter'
        ]
    ];

    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true,
        'Sds\DoctrineExtensions\Identity' => true,
    ];
}
