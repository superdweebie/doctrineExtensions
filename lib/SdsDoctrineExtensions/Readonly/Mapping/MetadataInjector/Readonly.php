<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Readonly\Mapping\MetadataInjector;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsDoctrineExtensions\AbstractMetadataInjector;
/**
 * Adds readonly values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Readonly extends AbstractMetadataInjector
{
    /**
     * Readonly
     */
    const readonly = 'readonly';

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
                if ($annot instanceof SDS_Readonly) {
                    $setMethod = $annot->setMethod;
                    if ($annot->setMethod == 'set*'){
                        $setMethod = 'set' . $property->getName();
                    }
                    $class->fieldMappings[$property->getName()][self::readonly] = array(
                        'value' => $annot->value,
                        'setMethod' => $setMethod
                    );
                }
            }
        }
    }
}
