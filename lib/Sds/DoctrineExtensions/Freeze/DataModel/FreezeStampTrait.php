<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\DataModel;

use Sds\DoctrineExtensions\Freeze\DataModel\FrozenOnTrait;
use Sds\DoctrineExtensions\Freeze\DataModel\FrozenByTrait;
use Sds\DoctrineExtensions\Freeze\DataModel\ThawedOnTrait;
use Sds\DoctrineExtensions\Freeze\DataModel\ThawedByTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait FreezeStampTrait {
   use FrozenOnTrait;
   use FrozenByTrait;
   use ThawedByTrait;
   use ThawedOnTrait;
}