<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer\Reference;

use Sds\DoctrineExtensions\DocumentManagerAwareInterface;
use Sds\DoctrineExtensions\DocumentManagerAwareTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SimpleLazy implements ReferenceSerializerInterface, DocumentManagerAwareInterface {

    use DocumentManagerAwareTrait;

    public function serialize($id, array $mapping){

        return $this->documentManager->getClassMetadata($mapping['targetDocument'])->collection . '/' . $id;
    }
}
