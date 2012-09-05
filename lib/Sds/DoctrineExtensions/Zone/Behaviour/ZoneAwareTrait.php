<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Zone\Behaviour;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Implements Sds\Common\ZoneAwareObjectTrait
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait ZoneAwareTrait {

    /**
     * @ODM\Field(type="hash")
     * @Sds\ZonesField
     * @Sds\ValidatorGroup(@Sds\Validator(class = "Sds\Common\Validator\IdentifierArrayValidator"))
     */
    protected $zones = array();

    /**
     * Set all possible zones
     *
     * @param array $zones An array of strings which are zone names
     */
    public function setZones(array $zones){
        $this->zones = $zones;
    }

    /**
     * Add a zone to the existing zone array
     *
     * @param string $zone
     */
    public function addZone($zone){
        $this->zones[] = (string) $zone;
    }

    /**
     *
     * @param string $zone
     */
    public function removeZone($zone){
        if(($key = array_search($zone, $this->zones)) !== false)
        {
            unset($this->zones[$key]);
        }
    }

    /**
     * Get the zone array
     *
     * @return array
     */
    public function getZones(){
        return $this->zones;
    }
}
