<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Stamp;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements ActiveUserAwareInterface {

    use ActiveUserAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected $dependencies = array(
        'SdsDoctrineExtensions\Readonly' => null
    );
}
