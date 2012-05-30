<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use SdsDoctrineExtensions\Stamp\Behaviour\CreatedOnTrait;
use SdsDoctrineExtensions\Stamp\Behaviour\CreatedByTrait;      
use SdsDoctrineExtensions\Stamp\Behaviour\UpdatedOnTrait;
use SdsDoctrineExtensions\Stamp\Behaviour\UpdatedByTrait;

trait StampTrait {
   use CreatedOnTrait, CreatedByTrait, UpdatedOnTrait, UpdatedByTrait;
}