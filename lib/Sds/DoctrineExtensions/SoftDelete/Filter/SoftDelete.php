<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Filter;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Query\Filter\BsonFilter;

/**
 * When this filter is enabled, all soft deleted documents will be filtered out
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
class SoftDelete extends BsonFilter
{

    /**
     *
     * @var array
     */
    protected $parameters = array('softDeleted' => false);

    /**
     * Set the filter to return only documents which are not
     * soft deleted
     */
    public function onlyNotSoftDeleted(){
        $this->parameters['softDeleted'] = false;
    }

    /**
     * Set the filter to return only documents which are soft deleted
     */
    public function onlySoftDeleted(){
        $this->parameters['softDeleted'] = true;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadata $targetMetadata
     * @return array
     */
    public function addFilterCriteria(ClassMetadata $targetMetadata)
    {
        if (isset($targetMetadata->softDelete)) {
            return array($targetMetadata->softDelete => $this->parameters['softDeleted']);
        }
        return array();
    }
}
