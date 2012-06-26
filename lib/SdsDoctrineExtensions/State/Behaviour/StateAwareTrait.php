<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\State\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Audit\Mapping\Annotation\Audit as SDS_Audit;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait StateAwareTrait{

    /**
     * @ODM\Field(type="string")
     * @SDS_Audit
     * @SDS_StateField
     */
    protected $state;

    /**
     * Set the current resource state
     *
     * @param string $state
     */
    public function setState($state){
        $this->state = (string) $state;
    }

    /**
     * @return string
     */
    public function getState(){
        return $this->state;
    }
}
