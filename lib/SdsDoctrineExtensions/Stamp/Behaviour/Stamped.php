<?php

namespace SdsDoctrineExtensions\Stamp\Behaviour;

use SdsDoctrineExtensions\Stamp\Behaviour\CreatedOn,
    SdsDoctrineExtensions\Stamp\Behaviour\CreatedBy,      
    SdsDoctrineExtensions\Stamp\Behaviour\UpdatedOn,
    SdsDoctrineExtensions\Stamp\Behaviour\UpdatedBy;

trait Stamp {
   use CreatedOn, CreatedBy, UpdatedOn, UpdatedBy;
}