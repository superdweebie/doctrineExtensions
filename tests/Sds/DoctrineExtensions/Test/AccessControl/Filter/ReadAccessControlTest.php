<?php

namespace Sds\DoctrineExtensions\Test\AccessControl\Filter;

use Sds\DoctrineExtensions\AccessControl\Filter\ReadAccessControl;
use Sds\DoctrineExtensions\Test\BaseTest;

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