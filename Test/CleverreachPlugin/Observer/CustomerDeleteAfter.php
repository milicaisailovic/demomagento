<?php

namespace Test\CleverreachPlugin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Test\CleverreachPlugin\Service\Synchronization\Contracts\SynchronizationServiceInterface;

class CustomerDeleteAfter implements ObserverInterface
{
    /**
     * @var SynchronizationServiceInterface
     */
    private $synchronizationService;

    /**
     * CustomerDeleteAfter constructor.
     */
    public function __construct(
        SynchronizationServiceInterface $synchronizationService
    )
    {
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * Delete receiver on API when customer is deleted in shop.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $deletedCustomer = $observer->getEvent()->getCustomer()->getEmail();
        $this->synchronizationService->deleteReceiver($deletedCustomer);
    }
}
