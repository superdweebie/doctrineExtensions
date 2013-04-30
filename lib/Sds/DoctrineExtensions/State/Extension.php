<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'Sds\DoctrineExtensions\State\MainSubscriber',
        'Sds\DoctrineExtensions\State\AnnotationSubscriber',
        'Sds\DoctrineExtensions\State\AccessControl\StatePermissionSubscriber',
        'Sds\DoctrineExtensions\State\AccessControl\TransitionPermissionSubscriber'
    ];

    protected $filters = [
        'state' => 'Sds\DoctrineExtensions\State\Filter\State'
    ];

    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true,
    ];
}
