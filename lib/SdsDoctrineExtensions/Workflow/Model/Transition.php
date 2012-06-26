<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Workflow\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\Workflow\TransitionInterface;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsDoctrineExtensions\State;

/**
 * Implementation of SdsCommon\Workflow\TransitionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @ODM\EmbeddedDocument
 */
class Transition implements TransitionInterface
{
    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly
    */
    protected $fromState;

    /**
    * @ODM\Field(type="string")
    * @SDS_Readonly
    */
    protected $toState;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $fromState,
        $toState
    ){
        $this->fromState = (string) $fromState;
        $this->toState = (string) $toState;
    }

    /**
     *
     * @return string
     */
    public function getFromState() {
        return $this->fromState;
    }

    /**
     *
     * @return string
     */
    public function getToState() {
        return $this->toState;
    }

    /**
     * Return the action name for this transition.
     * Used for access control
     *
     * @return string
     */
    public function getAction() {
        return State\Transition::getAction($this->fromState, $this->toState);
    }
}
