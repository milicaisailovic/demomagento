<?php

namespace Test\CleverreachPlugin\Observer;

use Test\CleverreachPlugin\Service\Synchronization\Contracts\SynchronizationServiceInterface;

class CustomerRegisterSuccess extends SaveDataObserver
{
    /**
     * CustomerRegisterSuccess constructor.
     *
     * @param SynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        SynchronizationServiceInterface $synchronizationService
    )
    {
        parent::__construct($synchronizationService);
    }
}