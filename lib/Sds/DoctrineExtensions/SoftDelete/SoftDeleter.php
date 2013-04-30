<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete;

use Sds\DoctrineExtensions\DocumentManagerAwareInterface;
use Sds\DoctrineExtensions\DocumentManagerAwareTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SoftDeleter implements DocumentManagerAwareInterface
{

    use DocumentManagerAwareTrait;

    public function isSoftDeleted($document){
        $metadata = $this->documentManager->getClassMetadata(get_class($document));
        return $metadata->reflFields[$metadata->softDelete['flag']]->getValue($document);
    }

    public function softDelete($document){
        $metadata = $this->documentManager->getClassMetadata(get_class($document));
        $metadata->reflFields[$metadata->softDelete['flag']]->setValue($document, true);
    }

    public function restore($document){
        $metadata = $this->documentManager->getClassMetadata(get_class($document));
        $metadata->reflFields[$metadata->softDelete['flag']]->setValue($document, false);
    }
}
