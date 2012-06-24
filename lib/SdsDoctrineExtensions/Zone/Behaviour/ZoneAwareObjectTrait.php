<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Zone;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Zone\Mapping\Annotation\ZonesField as SDS_ZonesField;

/**
 * Implements SdsCommon\ZoneAwareObjectTrait
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait ZoneAwareObjectTrait {

    /** 
     * @ODM\Field(type="hash") 
     * @SDS_ZonesField 
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
     * Get the zone array
     *
     * @return array
     */
    public function getZones(){
        return $this->zones;
    }
}
