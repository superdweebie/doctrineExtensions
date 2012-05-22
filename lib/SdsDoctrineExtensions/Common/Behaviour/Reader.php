<?php

namespace SdsDoctrineExtensions\Common\Behaviour;

use Doctrine\Common\Annotations\Reader as AnnotationReader;

trait Reader {
  
    protected $annotationReader;
    
    public function setReader(AnnotationReader $annotationReader){
        $this->annotationReader = $annotationReader;
    }   
}