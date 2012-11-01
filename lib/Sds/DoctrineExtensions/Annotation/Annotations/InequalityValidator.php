<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Sds\Common\Validator\InequalityValidator as ValidatorConst;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class InequalityValidator extends Annotation
{
    const event = 'annotationInequalityValidator';
    
    public $value = true;
    
    public $compareValue = 0;
    
    public $operator = ValidatorConst::GREATER_THAN;    
}