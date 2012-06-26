<?php

namespace SdsDoctrineExtensionsTest\AccessControl;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document\Simple;

class AccessControlTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\AccessControl' => null));

        $this->configure(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }
}