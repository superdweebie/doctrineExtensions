<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\DoNotHardDelete;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements AnnotationReaderAwareInterface {

    use AnnotationReaderAwareTrait;
}