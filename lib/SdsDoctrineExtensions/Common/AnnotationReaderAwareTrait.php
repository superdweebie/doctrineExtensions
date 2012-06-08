<?php

namespace SdsDoctrineExtensions\Common;

use Doctrine\Common\Annotations\Reader as DoctrineAnnotationReader;

trait AnnotationReaderAwareTrait {
  
    protected $annotationReader;
    
    public function setReader(DoctrineAnnotationReader $annotationReader){
        $this->annotationReader = $annotationReader;
    }   
}