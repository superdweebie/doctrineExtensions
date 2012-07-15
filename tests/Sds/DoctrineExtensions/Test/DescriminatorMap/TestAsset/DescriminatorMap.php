<?php

namespace Sds\DoctrineExtensions\Test\DescriminatorMap\TestAsset;

use Sds\DoctrineExtensions\DescriminatorMap\DescriminatorMapInterface;

class DescriminatorMap implements DescriminatorMapInterface {

    public function getDescriminatorMap() {
        return array(
            'doca' => 'Sds\DoctrineExtensions\Test\DescriminatorMap\TestAsset\Document\DocA',
            'docb' => 'Sds\DoctrineExtensions\Test\DescriminatorMap\TestAsset\Document\DocB',            
        );
    }
}
