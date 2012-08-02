<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\SoftDelete\SoftDeletedByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftDeletedByTrait {

    /**
     * @ODM\Field(type="string")
     * @ODM\Index
     * @Sds\UiHints(label = "Soft Deleted by")
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
     */
    protected $softDeletedBy;

    /**
     *
     * @param string $username
     */
    public function setSoftDeletedBy($username){
        $this->softDeletedBy = (string) $username;
    }

    /**
     *
     * @return string
     */
    public function getSoftDeletedBy(){
        return $this->softDeletedBy;
    }
}