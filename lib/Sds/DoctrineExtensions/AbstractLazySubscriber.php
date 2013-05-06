<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractLazySubscriber implements LazySubscriberInterface {

    public function getSubscribedEvents(){
        return self::getStaticSubscribedEvents();
    }
}