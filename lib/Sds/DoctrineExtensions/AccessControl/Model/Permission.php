<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\AccessControl\PermissionInterface;
use Sds\DoctrineExtensions\AccessControl\Behaviour\PermissionTrait;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;


/**
 * Implementation of Sds\Common\AccessControl\PermissionInterface
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
