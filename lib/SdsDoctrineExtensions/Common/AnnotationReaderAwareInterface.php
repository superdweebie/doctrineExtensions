<?php

namespace SdsDoctrineExtensions\Common;

use Doctrine\Common\Annotations\Reader as DoctrineAnnotationReader;

interface AnnotationReaderAwareInterface {
    
    public function setReader(DoctrineAnnotationReader $annotationReader); 
}
