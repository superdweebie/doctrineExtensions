<?php

namespace SdsDoctrineExtensions\Common;

use Doctrine\Common\Annotations\Reader as DoctrineAnnotationReader;

interface AnnotationReaderInterface {
    
    public function setReader(DoctrineAnnotationReader $annotationReader); 
}
