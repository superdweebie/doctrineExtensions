<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer\Type;

/**
 * Serializes dataTime objects
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
interface TypeSerializerInterface {

    public static function serialize($value);

    public static function unserialize($value);
}
