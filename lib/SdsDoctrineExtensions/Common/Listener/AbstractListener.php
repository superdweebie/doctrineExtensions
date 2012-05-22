<?php

namespace SdsDoctrineExtensions\Common\Listener;

use Doctrine\Common\EventSubscriber;

abstract class AbstractListener implements EventSubscriber
{    
    abstract public function getSubscribedEvents(); 
}