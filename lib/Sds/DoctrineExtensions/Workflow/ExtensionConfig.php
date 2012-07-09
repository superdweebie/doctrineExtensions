<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow;

use Sds\DoctrineExtensions\AbstractExtensionConfig;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig
{
    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'Sds\DoctrineExtensions\State' => null,
        'Sds\DoctrineExtensions\Readonly' => null
    );
}
