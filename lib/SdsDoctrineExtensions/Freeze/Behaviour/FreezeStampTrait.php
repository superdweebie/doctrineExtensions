<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze\Behaviour;

use SdsDoctrineExtensions\Freeze\Behaviour\FrozenOnTrait;
use SdsDoctrineExtensions\Freeze\Behaviour\FrozenByTrait;
use SdsDoctrineExtensions\Freeze\Behaviour\ThawedOnTrait;
use SdsDoctrineExtensions\Freeze\Behaviour\ThawedByTrait;

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