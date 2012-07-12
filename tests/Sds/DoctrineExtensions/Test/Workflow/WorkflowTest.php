<?php

namespace Sds\DoctrineExtensions\Test\Workflow;

use Sds\DoctrineExtensions\Workflow\Events;
use Sds\DoctrineExtensions\Workflow\Model\Workflow;
use Sds\DoctrineExtensions\Workflow\Model\Transition;
use Sds\DoctrineExtensions\Workflow\Workflow as WorkflowHelper;
use Sds\DoctrineExtensions\Test\BaseTest;
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
     * @expectedException Sds\DoctrineExtensions\Workflow\Exception\BadWorkflowException
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
     * @expectedException Sds\DoctrineExtensions\Workflow\Exception\BadWorkflowException
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