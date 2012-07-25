<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow;

use Sds\Common\Workflow\TransitionInterface;

/**
 * Implementation of Sds\Common\Workflow\TransitionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Transition implements TransitionInterface
{

    protected $fromState;

    protected $toState;

    public function __construct($fromState, $toState){
        $this->fromState = (string) $fromState;
        $this->toState = (string) $toState;
    }

    public function getFromState() {
        return $this->fromState;
    }

    public function getToState() {
        return $this->toState;
    }
}
