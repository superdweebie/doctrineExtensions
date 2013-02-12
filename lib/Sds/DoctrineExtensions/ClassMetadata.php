<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */

namespace Sds\DoctrineExtensions;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata as DoctrineClassMetadata;

/**
 * Extends ClassMetadata to support Sds metadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ClassMetadata extends DoctrineClassMetadata
{

    /**
     * Determines which fields get serialized.
     *
     * @return array The names of all the fields that should be serialized.
     */
    public function __sleep()
    {
        return array_merge(parent::__sleep(), [
            'accessControl',
            'rest',
            'serializer',
            'zones'
        ]);
    }
}
