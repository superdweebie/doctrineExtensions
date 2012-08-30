<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Doctrine\Common\Annotations\AnnotationReader;
use Sds\DoctrineExtensions\AccessControl\Extension;
use Sds\DoctrineExtensions\Test\BaseTest;

class ConfigArrayTest extends BaseTest {

    protected $calls = array();

    public function setUp(){
        parent::setUp();

        $this->configActiveUser(true);

        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\AccessControl' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
    }

    public function testConfigArray(){

        $config = array(
            'ActiveUser' => $this->activeUser,
            'AnnotationReader' => new AnnotationReader(),
            'PermissionClass' => 'Sds\DoctrineExtensions\AccessControl\Model\Permission',
            'AccessControlCreate' => false,
            'AccessControlRead' => true,
            'AccessControlUpdate' => false,
            'AccessControlDelete' => true
        );

        $extension = new Extension($config);

        $this->assertEquals('Sds\DoctrineExtensions\AccessControl\Model\Permission', $extension->getConfig()->getPermissionClass());
        $this->assertFalse($extension->getConfig()->getAccessControlCreate());
        $this->assertTrue($extension->getConfig()->getAccessControlRead());
        $this->assertFalse($extension->getConfig()->getAccessControlUpdate());
        $this->assertTrue($extension->getConfig()->getAccessControlDelete());
    }
}
