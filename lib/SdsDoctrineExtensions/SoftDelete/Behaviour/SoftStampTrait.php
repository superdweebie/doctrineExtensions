<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\Behaviour;

use SdsDoctrineExtensions\SoftDelete\Behaviour\SoftDeletedOnTrait;
use SdsDoctrineExtensions\SoftDelete\Behaviour\SoftDeletedByTrait;
use SdsDoctrineExtensions\SoftDelete\Behaviour\SoftRestoredOnTrait;
use SdsDoctrineExtensions\SoftDelete\Behaviour\SoftRestoredByTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftStampTrait {
   use SoftDeletedOnTrait;
   use SoftDeletedByTrait;
   use SoftRestoredByTrait;
   use SoftRestoredOnTrait;
}