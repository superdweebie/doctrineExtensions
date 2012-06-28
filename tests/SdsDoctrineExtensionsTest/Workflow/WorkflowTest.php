<?php

namespace SdsDoctrineExtensionsTest\Workflow;

use SdsDoctrineExtensions\Workflow\Event\Events;
use SdsDoctrineExtensions\Workflow\Model\Workflow;
use SdsDoctrineExtensions\Workflow\Model\Transition;
use SdsDoctrineExtensions\Workflow\Workflow as WorkflowHelper;
use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\Workflow\TestAsset\Document\Simple;

class WorkflowTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        parent::setUp();

        $this->configActiveUser();

        $manifest = $this->getManifest(array('SdsDoctrineExtensions\Workflow' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\Workflow\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
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
        $eventManager->addEventListener(Events::updateWorkflowVars, $this);

        $workflow = new Workflow(
            'draft',
            array('draft', 'approved', 'published'),
            array(
                new Transition('draft', 'approved'),
                new Transition('approved', 'published'),
                new Transition('published', 'approved')
            )
        );

        $testDoc = new Simple();
        $testDoc->setName('version 1');
        $testDoc->setState('draft');
        $testDoc->setWorkflow($workflow);

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('draft', $testDoc->getState());

        $testDoc->setState('wrong');

        $documentManager->flush();

        $this->assertEquals('draft', $testDoc->getState());
        $this->assertTrue(isset($this->calls[Events::transitionDoesNotExist]));
        $this->assertFalse(isset($this->calls[Events::updateWorkflowVars]));

        $testDoc->setState('published');

        $this->calls = array();
        $documentManager->flush();

        $this->assertEquals('draft', $testDoc->getState());
        $this->assertTrue(isset($this->calls[Events::transitionDoesNotExist]));
        $this->assertFalse(isset($this->calls[Events::updateWorkflowVars]));

        $testDoc->setState('approved');

        $this->calls = array();
        $documentManager->flush();

        $this->assertEquals('approved', $testDoc->getState());
        $this->assertFalse(isset($this->calls[Events::transitionDoesNotExist]));
        $this->assertTrue(isset($this->calls[Events::updateWorkflowVars]));

        $testDoc->setState('published');

        $this->calls = array();
        $documentManager->flush();

        $this->assertEquals('published', $testDoc->getState());
        $this->assertFalse(isset($this->calls[Events::transitionDoesNotExist]));
        $this->assertTrue(isset($this->calls[Events::updateWorkflowVars]));
    }

    /**
     * @expectedException SdsDoctrineExtensions\Workflow\Exception\BadWorkflowException
     */
    public function testUnreachableState(){

        $workflow = new Workflow(
            'draft',
            array('draft', 'approved', 'published'),
            array(
                new Transition('draft', 'approved'),
                new Transition('published', 'approved')
            )
        );

        WorkflowHelper::checkIntegrity($workflow);
    }

    /**
     * @expectedException SdsDoctrineExtensions\Workflow\Exception\BadWorkflowException
     */
    public function testUnusedTransitions(){

        $workflow = new Workflow(
            'draft',
            array('draft', 'approved', 'published'),
            array(
                new Transition('draft', 'approved'),
                new Transition('approved', 'published'),
                new Transition('published', 'approved'),
                new Transition('rejected', 'draft')
            )
        );

        WorkflowHelper::checkIntegrity($workflow);
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}