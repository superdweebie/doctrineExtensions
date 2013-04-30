<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp\DataModel;

use Sds\DoctrineExtensions\Stamp\DataModel\CreatedOnTrait;
use Sds\DoctrineExtensions\Stamp\DataModel\CreatedByTrait;
use Sds\DoctrineExtensions\Stamp\DataModel\UpdatedOnTrait;
use Sds\DoctrineExtensions\Stamp\DataModel\UpdatedByTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait StampTrait {
   use CreatedOnTrait;
   use CreatedByTrait;
   use UpdatedOnTrait;
   use UpdatedByTrait;
}