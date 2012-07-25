<?php

namespace Sds\DoctrineExtensions\Test\Workflow\TestAsset;

use Sds\DoctrineExtensions\Workflow\AbstractWorkflow;
use Sds\DoctrineExtensions\Workflow\Transition;

class UnusedTransitionsWorkflow extends AbstractWorkflow {

    protected $startState = 'draft';

    protected $possibleStates = array('draft', 'approved', 'published');

    protected $transitions = array();

    public function __construct(){
        $this->transitions = array(
            new Transition('draft', 'approved'),
            new Transition('approved', 'published'),
            new Transition('published', 'approved'),
            new Transition('rejected', 'draft')
        );
    }

    public function update($document){
        $document->setNumStateChanges($document->getNumStateChanges() + 1);
    }
}