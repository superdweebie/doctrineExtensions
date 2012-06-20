<?php

namespace SdsDoctrineExtensionsTest\Serializer\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Serializer\Mapping\Annotation\DoNotSerialize as SDS_DoNotSerialize;
use SdsDoctrineExtensions\Serializer\Mapping\Annotation\SerializeGetter as SDS_SerializeGetter;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @SDS_DoNotSerialize
     */
    protected $password;


    /** @ODM\EmbedMany(targetDocument="Phonenumber") */
    protected $groups;

    /** @ODM\EmbedOne(targetDocument="Profile") */
    protected $profile;

    /**
     * @ODM\Field(type="string")
     * @SDS_SerializeGetter("location")
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

    public function setLocation($location) {
        $this->location = $location;
    }

    public function getGroups()
    {
        return $this->groups;
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
