<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Workflow\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\Workflow\WorkflowInterface;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;


/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait WorkflowAwareTrait {

    /**
     * @ODM\EmbedOne(
     *   targetDocument="SdsDoctrineExtensions\Workflow\Model\Workflow"
     * )
     * @SDS_Readonly
     */
    protected $workflow = [];

    /**
     *
     * @param \SdsCommon\Workflow\WorkflowInterface $workflow
     */
    public function setWorkflow(WorkflowInterface $workflow){
        $this->workflow = $workflow;
        $this->state = $workflow->getStartState();
    }

    /**
     *
     * @return \SdsCommon\Workflow\WorkflowInterface
     */
    public function getWorkflow(){
        return $this->workflow;
    }
}
