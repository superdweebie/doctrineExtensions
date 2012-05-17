<?php

namespace SdsDoctrineExtensions\Common;

class Utils {
  
    static public function getAllTraits($className){
        $allTraits = [];
        $allClasses = array_merge(array($className), self::getAllParents($className));
        foreach ($allClasses as $class){
            $allTraits = array_merge($allTraits, self::getAllTraitsHelper($class));
        }
        return $allTraits;
    }
    
    static protected function getAllTraitsHelper($className){
        $allTraits = [];        
        $traits = class_uses($className);
        foreach ($traits as $trait){
            $allTraits = array_merge($allTraits, self::getAllTraits($trait));
        }        
        return array_merge($traits, $allTraits);
    }
    
    static public function getAllParents($className){
        $allParents = [];
        $parent = get_parent_class($className);
        if($parent){
            $allParents = array_merge(array($parent), self::getAllParents($parent));
        };
        return $allParents;
    }
    
    static public function checkForTrait($object, $trait){        
        $traits = Utils::getAllTraits($object);
        if(isset($traits[$trait])){
            return true;
        }    
        return false;
    }
}
