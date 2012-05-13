<?php

namespace SdsDoctrineExtensions;

use SdsDoctrineExtensions\Stamped\CreatedOn,
    SdsDoctrineExtensions\Stamped\CreatedBy,      
    SdsDoctrineExtensions\Stamped\UpdatedOn,
    SdsDoctrineExtensions\Stamped\UpdatedBy;

trait Stamped {
   use CreatedOn, CreatedBy, UpdatedOn, UpdatedBy;
}