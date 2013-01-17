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
class Lazy implements ReferenceSerializerInterface {

    public static function serialize($id, array $mapping, DocumentManager $documentManager){

        $metadata = $documentManager->getClassMetadata($mapping['targetDocument']);
        $ref = $metadata->collection . '/' . $id;
        return ['$ref' => $ref];
    }
}
