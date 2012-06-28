<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Workflow;

use SdsCommon\Workflow\WorkflowInterface;
use SdsDoctrineExtensions\Workflow\Exception\BadWorkflowException;

/**
 * Workflow helper methods
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Workflow {

    /**
     * Check that a workflow makes sense
     *
     * @param \SdsCommon\Workflow\WorkflowInterface $workflow
     * @throws \Exception
     */
    static public function checkIntegrity(WorkflowInterface $workflow){

        //check that startState is in possibleStates
        if (!(in_array($workflow->getStartState(), $workflow->getPossibleStates()))){
            throw new \Exception(sprintf('startState %s is not in possibleStates', $workflow->getStartState()));
        }

        //check that every possibleState can be reached from startState via transitions
        if ($workflow->getPossibleStates() instanceof ArrayCollection) {
            $possibleStates = $workflow->getPossibleStates()->toArray();
        } else {
            $possibleStates = $workflow->getPossibleStates();
        }
        $visitedStates = array($workflow->getStartState());

        if ($workflow->getTransitions() instanceof ArrayCollection) {
            $unusedTransitions = $workflow->getTransitions()->toArray();
        } else {
            $unusedTransitions = $workflow->getTransitions();
        }

        do {
            $visitedCount = count($visitedStates);
            foreach($unusedTransitions as $key => $transition){
                foreach($visitedStates as $state){
                    if($transition->getFromState() == $state &&
                        !in_array($transition->getToState(), $visitedStates)
                    ){
                        $visitedStates[] = $transition->getToState();
                        unset($unusedTransitions[$key]);
                    }
                }
            }
        } while (count($visitedStates) > $visitedCount);

        if (count($visitedStates) != count($possibleStates)){
            throw new BadWorkflowException('defined transitions do not allow every possible state to be reached');
        }

        // Check for dead transitions
        foreach ($unusedTransitions as $transition) {
            if (!in_array($transition->getFromstate(), $visitedStates)) {
                throw new BadWorkflowException(sprintf(
                    'Workflow has a dead transition: %s to %s',
                    $transition->getFromState(),
                    $transition->getToState()
                ));
            }
        }

        return true;
    }
}