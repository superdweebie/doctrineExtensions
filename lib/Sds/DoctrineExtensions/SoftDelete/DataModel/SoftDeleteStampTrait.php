<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\DataModel;

use Sds\DoctrineExtensions\SoftDelete\DataModel\SoftDeletedOnTrait;
use Sds\DoctrineExtensions\SoftDelete\DataModel\SoftDeletedByTrait;
use Sds\DoctrineExtensions\SoftDelete\DataModel\RestoredOnTrait;
use Sds\DoctrineExtensions\SoftDelete\DataModel\RestoredByTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftDeleteStampTrait {
   use SoftDeletedOnTrait;
   use SoftDeletedByTrait;
   use RestoredByTrait;
   use RestoredOnTrait;
}