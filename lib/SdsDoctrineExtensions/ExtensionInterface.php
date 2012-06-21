<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
interface ExtensionInterface{

    /**
     * Return the config object
     *
     * @return \SdsDoctrineExtensions\AbstractExtensionConfig
     */
    public function getConfig();

    /**
     * Return an array of annotation namespaces
     * Array key must be the namespace
     * Array value must the the path
     *
     * @return array
     */
    public function getAnnotations();

    /**
     * Return an array of filter class names
     *
     * @return array
     */
    public function getFilters();

    /**
     * Return an array of instantated objects which implement
     * the \Doctrine\Common\EventSubscriber interface
     *
     * @return
     */
    public function getSubscribers();

    /**
     * Return an array of document namespaces
     * Array key must be the namespace
     * Array value must the the path
     *
     * @return array
     */
    public function getDocuments();
}
