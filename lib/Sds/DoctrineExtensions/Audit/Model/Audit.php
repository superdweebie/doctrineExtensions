<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use Sds\DoctrineExtensions\DoNotHardDelete\Mapping\Annotation\DoNotHardDelete as SDS_DoNotHardDelete;
use Sds\Common\Audit\AuditInterface;
use Sds\DoctrineExtensions\Audit\Behaviour\AuditTrait;

/**
 * Standard audit document
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @ODM\EmbeddedDocument
 * @SDS_DoNotHardDelete
 */
class Audit implements AuditInterface
{
    use AuditTrait;
}
