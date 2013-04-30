<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Doctrine\Common\EventSubscriber;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractLazySubscriber implements EventSubscriber {

    /**
     *
     * @return array
     */
    abstract public static function getStaticSubscribedEvents();

    public function getSubscribedEvents(){
        return self::getStaticSubscribedEvents();
    }
}