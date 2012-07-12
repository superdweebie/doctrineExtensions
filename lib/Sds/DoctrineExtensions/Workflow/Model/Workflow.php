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
use Sds\DoctrineExtensions\Annotations as Sds;

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
}
