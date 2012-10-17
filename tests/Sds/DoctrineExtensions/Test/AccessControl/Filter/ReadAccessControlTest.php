<?php

namespace Sds\DoctrineExtensions\Test\AccessControl\Filter;

use Sds\DoctrineExtensions\AccessControl\Filter\ReadAccessControl;
use Sds\DoctrineExtensions\Test\BaseTest;

class ReadAccessControlTest extends BaseTest {

    public function setUp(){
        parent::setUp();
        $this->configIdentity(true);
        $this->configDoctrine();
    }

    public function testGetIdentity(){

        $filter = new ReadAccessControl($this->documentManager);
        $filter->setRoles($this->identity->getRoles());

        $this->assertEquals($this->identity->getRoles(), $filter->getRoles());
    }
}