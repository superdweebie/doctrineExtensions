<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Doctrine\ODM\MongoDB\DocumentManager;

interface DocumentManagerAwareInterface
{
    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setDocumentManager(DocumentManager $documentManager);

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getDocumentManager();
}
