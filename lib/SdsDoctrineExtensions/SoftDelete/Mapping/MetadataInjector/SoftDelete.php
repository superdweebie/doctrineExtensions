<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\Mapping\MetadataInjector;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use SdsDoctrineExtensions\SoftDelete\Mapping\Annotation\SoftDelete as SDS_SoftDelete;
use SdsDoctrineExtensions\Common\AbstractMetadataInjector;

/**
 * Adds doNotHardDelete values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SoftDelete extends AbstractMetadataInjector
{
    /**
     * SoftDelete
     */
    const softDelete = 'softDelete';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadata $class)
    {
        $reflClass = $class->getReflectionClass();

        //Property annotations
        foreach ($reflClass->getProperties() as $property) {
            if ($class->isMappedSuperclass && !$property->isPrivate() || $class->isInheritedField($property->name)) {
                continue;
            }

            foreach ($this->reader->getPropertyAnnotations($property) as $annot) {
                if ($annot instanceof SDS_SoftDelete) {
                    $getMethod = $annot->getMethod;
                    if ($annot->getMethod == 'get*'){
                        $getMethod = 'get' . $property->getName();
                    }
                    $class->softDelete = array(
                        'field' => $property,
                        'getMethod' => $getMethod
                    );
                    return;
                }
            }
        }
    }
}
