<?php

namespace SdsDoctrineExtensions\Audit\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"}) 
 */
final class Audit extends Annotation
{
}