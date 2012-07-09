<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze\Behaviour;

use Sds\DoctrineExtensions\Freeze\Behaviour\FrozenOnTrait;
use Sds\DoctrineExtensions\Freeze\Behaviour\FrozenByTrait;
use Sds\DoctrineExtensions\Freeze\Behaviour\ThawedOnTrait;
use Sds\DoctrineExtensions\Freeze\Behaviour\ThawedByTrait;

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