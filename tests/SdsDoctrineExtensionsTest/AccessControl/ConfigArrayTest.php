<?php

namespace SdsDoctrineExtensionsTest\AccessControl;

use Doctrine\Common\Annotations\AnnotationReader;
use SdsDoctrineExtensions\AccessControl\Extension;
use SdsDoctrineExtensionsTest\BaseTest;

class ConfigArrayTest extends BaseTest {

    protected $calls = array();

    public function setUp(){
        parent::setUp();

        $this->configActiveUser(true);

        $manifest = $this->getManifest(array('SdsDoctrineExtensions\AccessControl' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testConfigArray(){

        $config = array(
            'ActiveUser' => $this->activeUser,
            'AnnotationReader' => new AnnotationReader(),
            'PermissionClass' => 'SdsDoctrineExtensions\AccessControl\Model\Permission',
            'AccessControlCreate' => false,
            'AccessControlRead' => true,
            'AccessControlUpdate' => false,
            'AccessControlDelete' => true
        );

        $extension = new Extension($config);

        $this->assertEquals('SdsDoctrineExtensions\AccessControl\Model\Permission', $extension->getConfig()->getPermissionClass());
        $this->assertFalse($extension->getConfig()->getAccessControlCreate());
        $this->assertTrue($extension->getConfig()->getAccessControlRead());
        $this->assertFalse($extension->getConfig()->getAccessControlUpdate());
        $this->assertTrue($extension->getConfig()->getAccessControlDelete());
    }
}
