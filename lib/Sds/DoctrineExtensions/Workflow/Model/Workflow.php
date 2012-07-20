<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow\Model;

use Sds\Common\Workflow\WorkflowInterface;
use Sds\DoctrineExtensions\Workflow\Behaviour\WorkflowTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Implementation of Sds\Common\Workflow\WorkflowInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @ODM\EmbeddedDocument
 */
class Workflow implements WorkflowInterface
{
    use WorkflowTrait;

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
}
