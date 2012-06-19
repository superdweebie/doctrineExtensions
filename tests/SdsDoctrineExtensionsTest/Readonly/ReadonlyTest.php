<?php

namespace SdsDoctrineExtensionsTest\Readonly;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensions\Readonly\Subscriber\Readonly as ReadonlySubscriber;
use SdsDoctrineExtensionsTest\Readonly\TestAsset\TestDoc;

class ReadonlyTest extends BaseTest {

    public function setUp(){
        
        parent::setUp();
        
        $reflection = new \ReflectionClass('\SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly');

        $this->configure(
            array('SdsDoctrineExtensionsTest\Readonly\TestAsset' => __DIR__ . '/TestAsset'),
            array(),
            array(new ReadonlySubscriber($this->annotationReader)),
            array($reflection->getFilename())
        );
    }
    
    public function testReadonly(){
        
        $documentManager = $this->documentManager;
        
        $testDoc = new TestDoc();
        
        $testDoc->setReadonlyField('cannot-change');
        $testDoc->setMutableField('can-change');
        
        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();
        
        $repository = $documentManager->getRepository('SdsDoctrineExtensionsTest\Readonly\TestAsset\TestDoc');
        $testDoc = null;
        $testDoc = $repository->find($id);
        
        $this->assertNotNull($testDoc);
        $this->assertEquals('cannot-change', $testDoc->getReadonlyField());
        $this->assertEquals('can-change', $testDoc->getMutableField());
        
        $testDoc->setReadonlyField('readonly-changed');
        $testDoc->setMutableField('mutable-changed');
        
        $documentManager->flush();   
        $documentManager->clear();  
        $testDoc = null;
        $testDoc = $repository->find($id);
        
        $this->assertNotNull($testDoc);
        $this->assertEquals('cannot-change', $testDoc->getReadonlyField());
        $this->assertEquals('mutable-changed', $testDoc->getMutableField());        
    }
}