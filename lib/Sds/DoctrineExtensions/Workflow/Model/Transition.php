<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\Workflow\TransitionInterface;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use Sds\DoctrineExtensions\Workflow\Behaviour\TransitionTrait;

/**
 * Implementation of Sds\Common\Workflow\TransitionInterface
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
