<?php

namespace Sds\DoctrineExtensions\Test\Crypt\TestAsset;

use Sds\Common\Crypt\KeyInterface;

class Key implements KeyInterface {

    public static function getKey() {

        return 'test key phrase';
    }
}