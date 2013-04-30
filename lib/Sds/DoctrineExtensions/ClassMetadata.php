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
        $serialized = parent::__sleep();

        if (isset($this->accessControl)){
            $serialized[] = 'accessControl';
        }
        if (isset($this->crypt)){
            $serialized[] = 'crypt';
        }
        if (isset($this->generator)){
            $serialized[] = 'generator';
        }
        if (isset($this->freeze)){
            $serialized[] = 'freeze';
        }
        if (isset($this->permissions)){
            $serialized[] = 'permissions';
        }
        if (isset($this->rest)){
            $serialized[] = 'rest';
        }
        if (isset($this->roles)){
            $serialized[] = 'roles';
        }
        if (isset($this->serializer)){
            $serialized[] = 'serializer';
        }
        if (isset($this->softDelete)){
            $serialized[] = 'softDelete';
        }
        if (isset($this->stamp)){
            $serialized[] = 'stamp';
        }
        if (isset($this->state)){
            $serialized[] = 'state';
        }
        if (isset($this->validator)){
            $serialized[] = 'validator';
        }
        if (isset($this->zones)){
            $serialized[] = 'zones';
        }

        return $serialized;
    }
}
