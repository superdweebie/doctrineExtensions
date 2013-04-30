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
interface LazySubscriberInterface extends EventSubscriber {

    /**
     *
     * @return array
     */
    public static function getStaticSubscribedEvents();
}