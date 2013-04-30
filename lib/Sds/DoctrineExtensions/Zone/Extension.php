<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Zone;

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
        'Sds\DoctrineExtensions\Zone\AnnotationSubscriber'
    ];

    protected $filters = [
        'zone' => 'Sds\DoctrineExtensions\Zone\Filter\Zone'
    ];

    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true,
    ];
}
