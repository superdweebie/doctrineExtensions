<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Readonly;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'Sds\DoctrineExtensions\Readonly\MainSubscriber',
        'Sds\DoctrineExtensions\Readonly\AnnotationSubscriber',
    ];

    /**
     *
     * @var array
     */
    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true,
    ];
}
