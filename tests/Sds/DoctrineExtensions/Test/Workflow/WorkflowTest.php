<?php

namespace Sds\DoctrineExtensions\Test\Workflow;

use Sds\DoctrineExtensions\Workflow\Events;
use Sds\DoctrineExtensions\Workflow\WorkflowService;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Workflow\TestAsset;
use Sds\DoctrineExtensions\Test\Workflow\TestAsset\Document\Simple;

class WorkflowTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configActiveUser();

        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\Workflow' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Workflow\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testBasicFunction(){

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::transitionDoesNotExist, $this);

        $testDoc = new Simple();
        $testDoc->setName('version 1');
        $testDoc->setState('draft');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('draft', $testDoc->getState());
        $this->assertEquals(null, $testDoc->getNumStateChanges());

        $testDoc->setState('wrong');

        $documentManager->flush();

        $this->assertEquals('draft', $testDoc->getState());
        $this->assertEquals(null, $testDoc->getNumStateChanges());
        $this->assertTrue(isset($this->calls[Events::transitionDoesNotExist]));

        $testDoc->setState('published');

        $this->calls = array();
        $documentManager->flush();

        $this->assertEquals('draft', $testDoc->getState());
        $this->assertEquals(null, $testDoc->getNumStateChanges());
        $this->assertTrue(isset($this->calls[Events::transitionDoesNotExist]));

        $testDoc->setState('approved');

        $this->calls = array();
        $documentManager->flush();

        $this->assertEquals('approved', $testDoc->getState());
        $this->assertEquals(1, $testDoc->getNumStateChanges());
        $this->assertFalse(isset($this->calls[Events::transitionDoesNotExist]));


        $testDoc->setState('published');

        $this->calls = array();
        $documentManager->flush();

        $this->assertEquals('published', $testDoc->getState());
        $this->assertEquals(2, $testDoc->getNumStateChanges());
        $this->assertFalse(isset($this->calls[Events::transitionDoesNotExist]));
    }

    /**
     * @expectedException Sds\DoctrineExtensions\Workflow\Exception\BadWorkflowException
     */
    public function testUnreachableState(){

        $workflow = new TestAsset\UnreachableStateWorkflow();
        WorkflowService::checkIntegrity($workflow);
    }

    /**
     * @expectedException Sds\DoctrineExtensions\Workflow\Exception\BadWorkflowException
     */
    public function testUnusedTransitions(){

        $workflow = new TestAsset\UnusedTransitionsWorkflow();
        WorkflowService::checkIntegrity($workflow);
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}