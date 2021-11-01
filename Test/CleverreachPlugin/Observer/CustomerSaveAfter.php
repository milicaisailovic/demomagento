<?php

namespace Test\CleverreachPlugin\Observer;

use Test\CleverreachPlugin\Service\Synchronization\SynchronizationService;

class CustomerSaveAfter extends SaveDataObserver
{
    /**
     * CustomerSaveAfter constructor.
     */
    public function __construct(
        SynchronizationService $synchronizationService
    )
    {
        parent::__construct($synchronizationService);
    }
}
