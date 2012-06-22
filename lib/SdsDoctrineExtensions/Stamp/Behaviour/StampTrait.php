<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Stamp\Behaviour;

use SdsDoctrineExtensions\Stamp\Behaviour\CreatedOnTrait;
use SdsDoctrineExtensions\Stamp\Behaviour\CreatedByTrait;
use SdsDoctrineExtensions\Stamp\Behaviour\UpdatedOnTrait;
use SdsDoctrineExtensions\Stamp\Behaviour\UpdatedByTrait;

/**
 * Implements \SdsCommon\Stamp\CreatedByInterface
 * Implements \SdsCommon\Stamp\CreatedOnInterface
 * Implements \SdsCommon\Stamp\UpdatedByInterface
 * Implements \SdsCommon\Stamp\UpdatedOnInterface
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