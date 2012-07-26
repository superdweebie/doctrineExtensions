<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to define the workflow a model will use
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS"})
 */
final class WorkflowClass extends Annotation
{
    const event = 'annotationWorkflowClass';

    const metadataKey = 'workflowClass';
}