<?php

namespace Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document;

use Doctrine\Common\Collections\ArrayCollection;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document */
class User {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $username;

    /**
     * @ODM\Field(type="string")
     * @Sds\DoNotSerialize
     */
    protected $password;


    /** @ODM\EmbedMany(targetDocument="Group") */
    protected $groups;

    /** @ODM\EmbedOne(targetDocument="Profile") */
    protected $profile;

    /**
     * @ODM\Field(type="string")
     * @Sds\Getter("location")
     * @Sds\Setter("defineLocation")
     */
    protected $location;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function location() {
        return $this->location;
    }

    public function defineLocation($location) {
        $this->location = $location;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups(array $groups){
        $this->groups = $groups;
    }

    public function addGroup(Group $group)
    {
        $this->groups[] = $group;
    }

    public function getProfile() {
        return $this->profile;
    }

    public function setProfile(Profile $profile) {
        $this->profile = $profile;
    }
}
