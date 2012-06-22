<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Stamp;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsDoctrineExtensions\ActiveUserConfigInterface;
use SdsDoctrineExtensions\ActiveUserConfigTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements ActiveUserConfigInterface {

    use ActiveUserConfigTrait;
}
