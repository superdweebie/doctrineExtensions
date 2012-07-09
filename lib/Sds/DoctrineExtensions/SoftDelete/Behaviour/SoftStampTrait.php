<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Behaviour;

use Sds\DoctrineExtensions\SoftDelete\Behaviour\SoftDeletedOnTrait;
use Sds\DoctrineExtensions\SoftDelete\Behaviour\SoftDeletedByTrait;
use Sds\DoctrineExtensions\SoftDelete\Behaviour\RestoredOnTrait;
use Sds\DoctrineExtensions\SoftDelete\Behaviour\RestoredByTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftStampTrait {
   use SoftDeletedOnTrait;
   use SoftDeletedByTrait;
   use RestoredByTrait;
   use RestoredOnTrait;
}