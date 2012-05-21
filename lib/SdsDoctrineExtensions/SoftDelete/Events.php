<?php

namespace SdsDoctrineExtensions\SoftDelete;

final class Events
{
    private function __construct() {}

    const preSoftDelete = 'preSoftDelete';

    const postSoftDelete = 'postSoftDelete';

    const preSoftRestore = 'preSoftRestore';

    const postSoftRestore = 'postSoftRestore';
}