<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer\Reference;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
interface ReferenceSerializerInterface{

    public static function serialize($id, array $mapping, DocumentManager $documentManager);
}
