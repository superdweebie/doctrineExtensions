<?php

namespace SdsDoctrineExtensions;

class Utils {
  
    static public function getAllTraits($className){
        $allTraits = [];        
        $traits = class_uses($className);
        foreach ($traits as $trait){
            $allTraits = array_merge($allTraits, self::getAllTraits($trait));
        }        
        return array_merge($traits, $allTraits);
    }
}
