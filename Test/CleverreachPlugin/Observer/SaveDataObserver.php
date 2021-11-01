<?php

namespace Test\CleverreachPlugin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Test\CleverreachPlugin\Service\DataModel\Receiver;
use Test\CleverreachPlugin\Service\Synchronization\SynchronizationService;

abstract class SaveDataObserver implements ObserverInterface
{
    /**
     * @var SynchronizationService
     */
    private $synchronizationService;

    /**
     * SaveDataObserver constructor.
     *
     * @param SynchronizationService $synchronizationService
     */
    public function __construct(SynchronizationService $synchronizationService)
    {
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * Save new or edited customer on API.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $deactivated = true;
        if ($customer->getExtensionAttributes()->getIsSubscribed() !== null) {
            $deactivated = $customer->getExtensionAttributes()->getIsSubscribed() ?
                0 : strtotime($customer->getCreatedAt());
        }

        $receiver = new Receiver($customer->getId(), $customer->getEmail(), strtotime($customer->getCreatedAt()),
            $deactivated, $customer->getFirstname(), $customer->getLastname());
        $this->synchronizationService->sendReceivers([$receiver]);
    }
}