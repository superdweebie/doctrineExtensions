<?php

namespace SdsDoctrineExtensions\Common\Behaviour;

use Doctrine\Common\Annotations\Reader as DoctrineAnnotationReader;

trait AnnotationReader {
  
    protected $annotationReader;
    
    public function setReader(DoctrineAnnotationReader $annotationReader){
        $this->annotationReader = $annotationReader;
    }   
}