<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp\Behaviour;

use Sds\DoctrineExtensions\Stamp\Behaviour\CreatedOnTrait;
use Sds\DoctrineExtensions\Stamp\Behaviour\CreatedByTrait;
use Sds\DoctrineExtensions\Stamp\Behaviour\UpdatedOnTrait;
use Sds\DoctrineExtensions\Stamp\Behaviour\UpdatedByTrait;

/**
 * Implements \Sds\Common\Stamp\CreatedByInterface
 * Implements \Sds\Common\Stamp\CreatedOnInterface
 * Implements \Sds\Common\Stamp\UpdatedByInterface
 * Implements \Sds\Common\Stamp\UpdatedOnInterface
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