<?php

namespace SdsDoctrineExtensions\Readonly\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"}) 
 */
final class Readonly extends Annotation
{
}