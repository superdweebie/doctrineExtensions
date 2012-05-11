<?php

namespace SdsDoctrineExtensions;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait User {
  
    /** 
     * @ODM\Field(type="string") 
     */
    protected $username; 
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    } 
}

