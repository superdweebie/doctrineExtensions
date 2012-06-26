<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Workflow\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsCommon\Workflow\WorkflowInterface;

/**
 * Implementation of SdsCommon\Workflow\WorkflowInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @ODM\EmbeddedDocument
 */
class Workflow implements WorkflowInterface
{
    /**
     * @ODM\Field(type="string")
     * @SDS_Readonly
     *
     * @var string
     */
    protected $startState;

    /**
     * @ODM\Field(type="hash")
     * @SDS_Readonly
     *
     * var array
     */
    protected $possibleStates;

    /**
     * @ODM\EmbedMany(
     *   targetDocument="SdsDoctrineExtensions\Workflow\Model\Transition"
     * )
     * @SDS_Readonly
     *
     * @var array
     */
    protected $possibleTransitions;

    /**
     * @ODM\Field(type="hash")
     *
     * @var array
     */
    protected $vars;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $startState,
        array $possibleStates,
        ArrayCollection $possibleTransforms,
        array $vars = array()
    ){
        $this->startState = (string) $startState;
        $this->possibleStates = $possibleStates;
        $this->possibleTransforms = $possibleTransforms;
        $this->vars = $vars;
        $this->checkIntegrity();
    }

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
    public function getPossibleTransitions() {
        return $this->possibleTransitions;
    }

    /**
     * An array to store arbitary workflow variables
     *
     * @return array
     */
    public function getVars() {
        return $this->vars;
    }

    /**
     *
     * @param array $vars
     */
    public function setVars(array $vars) {
        $this->vars = $vars;
    }

    /**
     * Check that the workflow makes sense
     */
    public function checkIntegrity(){

        //check that startState is in possibleStates
        if (!(in_array($this->startState, $this->possibleStates))){
            throw new \Exception(sprintf('startState %s is not in possibleStates', $this->startState));
        }

        //check that every possibleState can be reached from startState via possibleTransforms
        $possibleStates = $this->possibleStates->toArray();
        $visitedStates = array($this->startState);

        do {
            $visitedCount = count($visitedStates);
            foreach($this->possibleTransitions as $transition){
                foreach($visitedStates as $state){
                    if($transition->getFromState() == $state &&
                        !in_array($transition->getToState(), $visitedStates)
                    ){
                        $visitedStates[] = $state;
                    }
                }
            }
        } while (count($visitedStates > $visitedCount));

        if (count($visitedStates) != count($possibleStates)){
            throw new \Exception('defined transitions do not allow every possible state to be reached');
        }
    }
}
