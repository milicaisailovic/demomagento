<?php

namespace Test\CleverreachPlugin\Observer;

use Test\CleverreachPlugin\Service\Synchronization\Contracts\SynchronizationServiceInterface;

class CustomerSaveAfter extends SaveDataObserver
{
    /**
     * CustomerSaveAfter constructor.
     */
    public function __construct(
        SynchronizationServiceInterface $synchronizationService
    )
    {
        parent::__construct($synchronizationService);
    }
}
