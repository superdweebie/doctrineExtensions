<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Accessor;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Sds\DoctrineExtensions\AbstractMetadataInjector;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Adds requiresValidation values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MetadataInjector extends AbstractMetadataInjector
{
    /**
     * getter
     */
    const getter = 'getter';

    /**
     * setter
     */
    const setter = 'setter';

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

            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof Sds\Getter) {
                    $class->fieldMappings[$property->getName()][self::getter] = $annotation->value;
                }
                if ($annotation instanceof Sds\Setter) {
                    $class->fieldMappings[$property->getName()][self::setter] = $annotation->value;
                }
            }
        }
    }
}
