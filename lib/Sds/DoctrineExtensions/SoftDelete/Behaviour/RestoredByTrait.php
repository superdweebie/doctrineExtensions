<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \Sds\Common\SoftDelete\RestoredByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait RestoredByTrait {

    /**
     * @ODM\Field(type="string")
     * @ODM\Index
     * @Sds\UiHints(label = "Restored by")
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
     */
    protected $restoredBy;

    /**
     *
     * @param string $username
     */
    public function setRestoredBy($username){
        $this->restoredBy = (string) $username;
    }

    /**
     *
     * @return string
     */
    public function getRestoredBy(){
        return $this->restoredBy;
    }
}
