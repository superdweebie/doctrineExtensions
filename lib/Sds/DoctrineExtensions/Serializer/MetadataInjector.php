<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Sds\DoctrineExtensions\Annotations as Sds;
use Sds\DoctrineExtensions\AbstractMetadataInjector;

/**
 * Adds serialization values to metadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MetadataInjector extends AbstractMetadataInjector
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
                if ($annot instanceof Sds\DoNotSerialize) {
                    $class->fieldMappings[$property->getName()][self::doNotSerialize] = true;
                }

                if ($annot instanceof Sds\SerializeGetter) {
                    $class->fieldMappings[$property->getName()][self::serializeGetter] = $annot->value;
                }
            }
        }
    }
}
