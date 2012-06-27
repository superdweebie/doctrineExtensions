<?php

namespace SdsDoctrineExtensionsTest\AccessControl\Filter;

use SdsDoctrineExtensions\AccessControl\Filter\ReadAccessControl;
use SdsDoctrineExtensionsTest\BaseTest;

class ReadAccessControlTest extends BaseTest {

    public function setUp(){
        parent::setUp();
        $this->configActiveUser(true);
        $this->configDoctrine();
    }

    public function testGetActiveUser(){

        $filter = new ReadAccessControl($this->documentManager);
        $filter->setActiveUser($this->activeUser);

        $this->assertEquals($this->activeUser, $filter->getActiveUser());
    }
}