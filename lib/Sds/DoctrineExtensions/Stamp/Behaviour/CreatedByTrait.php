<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp\Behaviour;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Implements \Sds\Common\Stamp\CreatedByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait CreatedByTrait {

    /**
     * @ODM\Field(type="string")
     * @Sds\Readonly
     */
    protected $createdBy;

    /**
     *
     * @param string $username
     */
    public function setCreatedBy($username){
        $this->createdBy = (string) $username;
    }

    /**
     *
     * @return string
     */
    public function getCreatedBy(){
        return $this->createdBy;
    }
}
