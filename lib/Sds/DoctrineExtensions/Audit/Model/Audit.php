<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit\Model;

use Sds\Common\Audit\AuditInterface;
use Sds\DoctrineExtensions\Audit\Behaviour\AuditTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Standard audit document
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @ODM\EmbeddedDocument
 * @Sds\DoNotHardDelete
 */
class Audit implements AuditInterface
{
    use AuditTrait;
}
