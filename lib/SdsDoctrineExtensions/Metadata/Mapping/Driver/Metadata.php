<?php

namespace SdsDoctrineExtensions\Metadata\Mapping\Driver;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use SdsDoctrineExtensions\Metadata\Mapping\Annotation\Hidden as SDS_Hidden;
use SdsDoctrineExtensions\Metadata\Mapping\Annotation\Label as SDS_Label;
use SdsDoctrineExtensions\Metadata\Mapping\Annotation\Width as SDS_Width;

class Readonly
{
    const HIDDEN = 'hidden';
    const LABEL = 'label';
    const WIDTH = 'width';
    
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

            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof SDS_Hidden) {
                    $class->fieldMappings[$property->getName()][self::HIDDEN] = true;
                }
                if ($annotation instanceof SDS_Label) {
                    $class->fieldMappings[$property->getName()][self::LABEL] = $annotation->value;
                }                  
                if ($annotation instanceof SDS_Width) {
                    $class->fieldMappings[$property->getName()][self::WIDTH] = $annotation->value;
                }                
            }
        }
    }      
}
