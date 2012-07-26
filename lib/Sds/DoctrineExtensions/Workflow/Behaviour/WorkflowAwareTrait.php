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
}
