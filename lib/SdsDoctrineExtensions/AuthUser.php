<?php

namespace SdsDoctrineExtensions;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait AuthUser {

    use User;
    
    protected $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';    
    protected $saltLength = 50;

    /** 
     * @ODM\Field(type="string") 
     */
    protected $password;
    
    /** 
     * @ODM\Field(type="boolean") 
     */    
    protected $isGuest;    
    
    public function isGuest() {
        return $this->isGuest;
    }

    public function setIsGuest($isGuest) {
        $this->isGuest = $isGuest;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getSaltLength() {
        return $this->saltLength;
    }
    
    public function setPassword($plaintext) {
        $preSalt = substr(str_shuffle(str_repeat($this->chars,10)),0,$this->saltLength);
        $postSalt = substr(str_shuffle(str_repeat($this->chars,10)),0,$this->saltLength);        
        $this->password = $this::_hashPassword($plaintext, $preSalt, $postSalt);
    }
    
    public static function _hashPassword($plaintext, $preSalt, $postSalt){
        return $preSalt.sha1($preSalt.$plaintext.$postSalt).$postSalt;
    }
    
    public static function hashPassword($identity, $plaintext){       
        
        if(!($identity instanceof self)){
            throw new \Exception('Identity passed to HashPassword must be instance of class that exhibits AuthUser trait.');
        }
        $saltLength = $identity->getSaltLength();
        $dbPassword = $identity->getPassword();
        $preSalt = substr($dbPassword, 0, $saltLength);
        $postSalt = substr($dbPassword, strlen($dbPassword) - $saltLength, $saltLength);
        return $identity::_hashPassword($plaintext, $preSalt, $postSalt);
    }      
}
