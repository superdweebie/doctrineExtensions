<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Workflow\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait TransitionTrait {

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
    public function __construct($fromState, $toState){
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
}
