<?php

namespace Test\CleverreachPlugin\Observer;

use Test\CleverreachPlugin\Service\Synchronization\SynchronizationService;

class CustomerRegisterSuccess extends SaveDataObserver
{
    /**
     * CustomerRegisterSuccess constructor.
     *
     * @param SynchronizationService $synchronizationService
     */
    public function __construct(
        SynchronizationService $synchronizationService
    )
    {
        parent::__construct($synchronizationService);
    }
}