<?php

namespace SdsDoctrineExtensions\Common;

use Doctrine\Common\Annotations\Reader as AnnotationReader;

trait Reader {
  
    protected $annotationReader;
    
    protected $driverChain;
    
    public function setReader(AnnotationReader $annotationReader){
        $this->annotationReader = $annotationReader;
    }
    
    public function setDriverChain($driverChain){
        $this->driverChain = $driverChain;
        $this->reader = $this->driverChain->getCachedReader();        
    }    
}