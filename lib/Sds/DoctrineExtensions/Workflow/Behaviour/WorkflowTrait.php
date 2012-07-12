<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow\Behaviour;

use Sds\DoctrineExtensions\Workflow\Workflow as WorkflowHelper;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;


/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait WorkflowTrait {

    /**
     * @ODM\Field(type="string")
     * @Sds\Readonly
     *
     * @var string
     */
    protected $startState;

    /**
     * @ODM\Field(type="hash")
     * @Sds\Readonly
     *
     * var array
     */
    protected $possibleStates;

    /**
     * @ODM\EmbedMany(
     *   targetDocument="Sds\DoctrineExtensions\Workflow\Model\Transition"
     * )
     * @Sds\Readonly
     *
     * @var array
     */
    protected $transitions;

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
        array $transitions,
        array $vars = array()
    ){
        $this->startState = (string) $startState;
        $this->possibleStates = $possibleStates;
        $this->transitions = $transitions;
        $this->vars = $vars;
        WorkflowHelper::checkIntegrity($this);
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
    public function getTransitions() {
        return $this->transitions;
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
}
