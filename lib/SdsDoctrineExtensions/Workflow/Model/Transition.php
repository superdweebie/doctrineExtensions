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
use SdsDoctrineExtensions\Workflow\Behaviour\TransitionTrait;

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
    use TransitionTrait;
}
