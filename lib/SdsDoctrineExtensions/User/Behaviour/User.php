<?php

namespace SdsDoctrineExtensions\User\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

trait User {
  
    /** 
     * @ODM\Field(type="string") 
     * @SDS_Readonly
     */
    protected $username; 
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    } 
}

