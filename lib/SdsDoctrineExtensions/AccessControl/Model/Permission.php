<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\AccessControl\PermissionInterface;
use SdsDoctrineExtensions\AccessControl\Behaviour\PermissionTrait;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;


/**
 * Implementation of SdsCommon\AccessControl\PermissionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @ODM\EmbeddedDocument
 */
class Permission implements PermissionInterface
{
    use PermissionTrait;
}
