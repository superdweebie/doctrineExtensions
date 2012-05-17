<?php

namespace SdsDoctrineExtensions\Serializer\Mapping\Driver;

use Doctrine\Common\Annotations\Reader,
    Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo,
    SdsDoctrineExtensions\Serializer\Mapping\Annotation\DoNotSerialize as SDS_DoNotSerialize;

class Serializer
{
   
    const DO_NOT_SERIALIZE = 'doNotSerialize';
    
    /**
     * The annotation reader.
     *
     * @var Reader
     */
    private $reader;
    
    /**
     * Initializes a new AnnotationDriver that uses the given Reader for reading
     * docblock annotations.
     *
     * @param $reader Reader The annotation reader to use.
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }   
    
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
                    $class->fieldMappings[$property->getName()][self::DO_NOT_SERIALIZE] = true;
                }
            }
        }
    }      
}
