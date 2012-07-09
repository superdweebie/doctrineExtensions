<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DoNotHardDelete\Mapping\MetadataInjector;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Sds\DoctrineExtensions\DoNotHardDelete\Mapping\Annotation\DoNotHardDelete as SDS_DoNotHardDelete;
use Sds\DoctrineExtensions\AbstractMetadataInjector;

/**
 * Adds doNotHardDelete values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DoNotHardDelete extends AbstractMetadataInjector
{
    /**
     * DoNotHardDelete
     */
    const doNotHardDelete = 'doNotHardDelete';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadataInfo $class)
    {
        foreach ($this->reader->getClassAnnotations($class->reflClass) as $annot) {
            if ($annot instanceof SDS_DoNotHardDelete) {
                $class->doNotHardDelete = true;
            }
        }
    }
}
