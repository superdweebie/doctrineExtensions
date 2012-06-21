<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Serializer\Mapping\MetadataInjector;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use SdsDoctrineExtensions\Serializer\Mapping\Annotation\DoNotSerialize as SDS_DoNotSerialize;
use SdsDoctrineExtensions\Serializer\Mapping\Annotation\SerializeGetter as SDS_SerializeGetter;
use SdsDoctrineExtensions\AbstractMetadataInjector;

/**
 * Adds serialization values to metadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Serializer extends AbstractMetadataInjector
{

    /**
     * doNotSerialize Annotation name
     */
    const doNotSerialize = 'doNotSerialize';

    /**
     * serializeGetter Annotation name
     */
    const serializeGetter = 'serializeGetter';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadataInfo $class)
    {
        $reflClass = $class->getReflectionClass();

        //Property annotations
        foreach ($reflClass->getProperties() as $property) {
            if ($class->isMappedSuperclass && !$property->isPrivate() || $class->isInheritedField($property->name)) {
                continue;
            }

            foreach ($this->reader->getPropertyAnnotations($property) as $annot) {
                if ($annot instanceof SDS_DoNotSerialize) {
                    $class->fieldMappings[$property->getName()][self::doNotSerialize] = true;
                }

                if ($annot instanceof SDS_SerializeGetter) {
                    $class->fieldMappings[$property->getName()][self::serializeGetter] = $annot->value;
                }
            }
        }
    }
}
