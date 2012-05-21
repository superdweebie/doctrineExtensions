<?php

namespace SdsDoctrineExtensions\AccessControl;

final class Events
{
    private function __construct() {}

    const createDenied = 'createDenied';

    const updateDenied = 'updateDenied';

    const deleteDenied = 'deleteDenied';
    
    const restoreDenied = 'restoreDenied';
}