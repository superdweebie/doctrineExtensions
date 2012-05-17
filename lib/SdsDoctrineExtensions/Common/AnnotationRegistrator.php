<?php

namespace SdsDoctrineExtensions\Common;

use Doctrine\Common\Annotations\AnnotationRegistry;
    
class AnnotationRegistrator {
    
    protected $annotations = [
        'SdsDoctrineExtensions\Audit\Mapping\Annotation' => 'Audit',
        'SdsDoctrineExtensions\Readonly\Mapping\Annotation' => 'Readonly',
        'SdsDoctrineExtensions\Serializer\Mapping\Annotation' => 'DoNotSerialize'        
    ];
    
    public function registerAll(){
       
        foreach ($this->annotations as $namespace => $class){
            $annotationReflection = new \ReflectionClass($namespace.'\\'.$class);
            $path = dirname($annotationReflection->getFileName());        
            AnnotationRegistry::registerAutoloadNamespace(
                $namespace, 
                $path
            );            
        }
    }
}