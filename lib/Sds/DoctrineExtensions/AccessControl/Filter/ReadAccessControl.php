<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\Filter;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Query\Filter\BsonFilter;
use Sds\DoctrineExtensions\AccessControl\Actions;
use Sds\DoctrineExtensions\AccessControl\AccessController;

/**
 * When this filter is enabled, will filter out all documents
 * the active identity does not have permission to read.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ReadAccessControl extends BsonFilter
{

    protected $accessController;

    public function setAccessController(AccessController $accessController) {
        $this->accessController = $accessController;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadata $targetDocument
     * @return array
     */
    public function addFilterCriteria(ClassMetadata $metadata)
    {
        $accessController = $this->accessController;
        $result = $accessController->areAllowed([Actions::read], $metadata);

        if ($result->hasCriteria()){
            return $result->getNew();
        } else {
            if ($result->getAllowed()){
                return []; //allow read
            } else {
                return [$metadata->identifier => ['$exists' => false]]; //deny read
            }
        }
    }
}
