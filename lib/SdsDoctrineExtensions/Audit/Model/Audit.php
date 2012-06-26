<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Audit\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsDoctrineExtensions\DoNotHardDelete\Mapping\Annotation\DoNotHardDelete as SDS_DoNotHardDelete;
use SdsCommon\Audit\AuditInterface;
use SdsDoctrineExtensions\Audit\Behaviour\AuditTrait;

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
