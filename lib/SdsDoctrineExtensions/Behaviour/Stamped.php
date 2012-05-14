<?php

namespace SdsDoctrineExtensions\Behaviour;

use SdsDoctrineExtensions\Behaviour\Stamped\CreatedOn,
    SdsDoctrineExtensions\Behaviour\Stamped\CreatedBy,      
    SdsDoctrineExtensions\Behaviour\Stamped\UpdatedOn,
    SdsDoctrineExtensions\Behaviour\Stamped\UpdatedBy;

trait Stamped {
   use CreatedOn, CreatedBy, UpdatedOn, UpdatedBy;
}