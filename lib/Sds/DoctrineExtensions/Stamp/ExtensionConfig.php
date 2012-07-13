<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp;

use Sds\DoctrineExtensions\AbstractExtensionConfig;
use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\ActiveUserAwareTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements ActiveUserAwareInterface {

    use ActiveUserAwareTrait;
}
