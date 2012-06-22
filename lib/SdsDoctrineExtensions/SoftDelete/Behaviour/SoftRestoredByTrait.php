<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \SdsCommon\SoftDelete\SoftRestoredByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait SoftRestoredByTrait {

    /**
     * @ODM\Field(type="string")
     */
    protected $restoredBy;

    /**
     *
     * @param string $username
     */
    public function setSoftRestoredBy($username){
        $this->restoredBy = (string) $username;
    }

    /**
     *
     * @return string
     */
    public function getSoftRestoredBy(){
        return $this->restoredBy;
    }
}
