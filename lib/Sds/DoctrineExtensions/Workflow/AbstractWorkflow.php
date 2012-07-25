<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow;

use Sds\Common\Workflow\WorkflowInterface;

/**
 * Implementation of Sds\Common\Workflow\WorkflowInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractWorkflow implements WorkflowInterface
{

    /**
     * @var string
     */
    protected $startState;

    /**
     * var array
     */
    protected $possibleStates;

    /**
     * @var array
     */
    protected $transitions;

    /**
     * The state the object will be in immediately after creation
     *
     * @return string
     */
    public function getStartState() {
        return $this->startState;
    }

    /**
     * An array of strings will all possible state names
     *
     * @return array
     */
    public function getPossibleStates() {
        return $this->possibleStates;
    }

    /**
     * An array of Transition documents
     *
     * @return array
     */
    public function getTransitions() {
        return $this->transitions;
    }

}
