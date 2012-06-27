<?php

namespace SdsDoctrineExtensionsTest\AccessControl\Behaviour;

use SdsDoctrineExtensions\AccessControl\Model\Permission;
use SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document\Simple;
use SdsDoctrineExtensionsTest\BaseTest;

class AccessControlledTraitTest extends BaseTest {

    public function testAddPermission(){

        $testDoc = new Simple();

        $testDoc->addPermission(new Permission('role', 'action'));
        $permission = $testDoc->getPermissions()[0];

        $this->assertEquals('role', $permission->getRole());
        $this->assertEquals('action', $permission->getAction());
    }

    public function testRemovePermission(){

        $testDoc = new Simple();

        $testDoc->addPermission(new Permission('role', 'action'));
        $permissions = $testDoc->getPermissions();

        $this->assertCount(1, $permissions);

        $testDoc->removePermission($permissions[0]);
        $permissions = $testDoc->getPermissions();

        $this->assertCount(0, $permissions);
    }
}