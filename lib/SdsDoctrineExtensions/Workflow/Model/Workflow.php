<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Workflow\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\Workflow\WorkflowInterface;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsDoctrineExtensions\Workflow\Behaviour\WorkflowTrait;

/**
 * Implementation of SdsCommon\Workflow\WorkflowInterface
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
