<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\Workflow\WorkflowInterface;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use Sds\DoctrineExtensions\State\Behaviour\StateAwareTrait;

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
     * @SDS_Readonly
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
