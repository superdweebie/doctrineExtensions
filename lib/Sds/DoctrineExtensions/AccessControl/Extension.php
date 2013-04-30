<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

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
        'Sds\DoctrineExtensions\AccessControl\MainSubscriber',
        'Sds\DoctrineExtensions\AccessControl\AnnotationSubscriber',
        'Sds\DoctrineExtensions\AccessControl\BasicPermissionSubscriber'
    ];

    protected $filters = [
        'readAccessControl' => 'Sds\DoctrineExtensions\AccessControl\Filter\ReadAccessControl'
    ];

    protected $defaultServiceManagerConfig = [
        'invokables' => [
            'accessController' => 'Sds\DoctrineExtensions\AccessControl\AccessController'
        ]
    ];

    /**
     *
     * @var array
     */
    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true,
        'Sds\DoctrineExtensions\Identity' => true,
    ];
}
