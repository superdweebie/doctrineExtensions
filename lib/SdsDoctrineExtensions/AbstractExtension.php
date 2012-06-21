<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

/**
 * A base class which extensions may extend
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractExtension implements ExtensionInterface {

    /**
     *
     * @var \SdsDoctrineExtensions\AbstractExtensionConfig
     */
    protected $config;

    /**
     *
     * @var array
     */
    protected $annotations = array();

    /**
     *
     * @var array
     */
    protected $filters = array();

    /**
     *
     * @var array
     */
    protected $subscribers = array();

    /**
     *
     * @var array
     */
    protected $documents = array();

    /**
     *
     * @param \SdsDoctrineExtensions\AbstractExtensionConfig $config
     */
    public function __construct(AbstractExtensionConfig $config){
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(){
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getAnnotations(){
        return $this->annotations;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(){
        return $this->filters;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribers(){
        return $this->subscribers;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocuments(){
        return $this->documents;
    }
}
