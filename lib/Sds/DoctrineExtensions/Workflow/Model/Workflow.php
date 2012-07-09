<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\Workflow\WorkflowInterface;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use Sds\DoctrineExtensions\Workflow\Behaviour\WorkflowTrait;

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
