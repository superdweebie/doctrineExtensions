<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow\Behaviour;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotationa as Sds;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait TransitionTrait {

    /**
     * @ODM\Field(type="string")
     * @Sds\Readonly
     * @Sds\UiHints(label = "From State")
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
     */
    protected $fromState;

    /**
     * @ODM\Field(type="string")
     * @Sds\Readonly
     * @Sds\UiHints(label = "To State")
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
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
