<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Sds\DoctrineExtensions\DocumentManagerAwareInterface;
use Sds\DoctrineExtensions\DocumentManagerAwareTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Freezer implements DocumentManagerAwareInterface
{

    use DocumentManagerAwareTrait;

    public function isFrozen($document){
        $metadata = $this->documentManager->getClassMetadata(get_class($document));
        return $metadata->reflFields[$metadata->freeze['flag']]->getValue($document);
    }

    public function freeze($document){
        $metadata = $this->documentManager->getClassMetadata(get_class($document));
        $metadata->reflFields[$metadata->freeze['flag']]->setValue($document, true);
    }

    public function thaw($document){
        $metadata = $this->documentManager->getClassMetadata(get_class($document));
        $metadata->reflFields[$metadata->freeze['flag']]->setValue($document, false);
    }
}
