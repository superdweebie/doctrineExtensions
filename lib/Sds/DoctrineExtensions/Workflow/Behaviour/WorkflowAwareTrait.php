<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow\Behaviour;

use Sds\Common\Workflow\WorkflowInterface;
use Sds\DoctrineExtensions\State\Behaviour\StateAwareTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait WorkflowAwareTrait {

    use StateAwareTrait;

    /**
     * @ODM\EmbedOne(
     *   targetDocument="Sds\DoctrineExtensions\Workflow\Model\Workflow"
     * )
     * @Sds\Readonly
     * @Sds\UiHints(label = "Workflow")
     */
    protected $workflow = [];

    /**
     *
     * @param \Sds\Common\Workflow\WorkflowInterface $workflow
     */
    public function setWorkflow(WorkflowInterface $workflow){
        $this->workflow = $workflow;
        $this->state = $workflow->getStartState();
    }

    /**
     *
     * @return \Sds\Common\Workflow\WorkflowInterface
     */
    public function getWorkflow(){
        return $this->workflow;
    }
}
