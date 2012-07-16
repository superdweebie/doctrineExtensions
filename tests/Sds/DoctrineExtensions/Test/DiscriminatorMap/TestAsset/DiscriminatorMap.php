<?php

namespace Sds\DoctrineExtensions\Test\DiscriminatorMap\TestAsset;

use Sds\DoctrineExtensions\DiscriminatorMap\DiscriminatorMapInterface;

class DiscriminatorMap implements DiscriminatorMapInterface {

    public function getDiscriminatorMap() {
        return array(
            'doca' => 'Sds\DoctrineExtensions\Test\DiscriminatorMap\TestAsset\Document\DocA',
            'docb' => 'Sds\DoctrineExtensions\Test\DiscriminatorMap\TestAsset\Document\DocB',
        );
    }
}
