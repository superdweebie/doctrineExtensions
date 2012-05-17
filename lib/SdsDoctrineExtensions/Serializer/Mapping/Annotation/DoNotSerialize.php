<?php

namespace SdsDoctrineExtensions\Serializer\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"}) 
 */
final class DoNotSerialize extends Annotation
{
}