<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\UiHints;

use Sds\DoctrineExtensions\AbstractMetadataInjector;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Adds UiHints values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MetadataInjector extends AbstractMetadataInjector
{
    /**
     * uiHints
     */
    const uiHints = 'uiHints';

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
                if ($annotation instanceof Sds\UiHints) {
                    $class->fieldMappings[$property->getName()][self::uiHints] = get_object_vars($annotation);
                }
            }
        }
    }
}
