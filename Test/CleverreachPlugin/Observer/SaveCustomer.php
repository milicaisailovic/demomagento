<?php

namespace Test\CleverreachPlugin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Test\CleverreachPlugin\Service\Synchronization\Contracts\SynchronizationServiceInterface;
use Test\CleverreachPlugin\Service\Synchronization\DTO\Receiver;

class SaveCustomer implements ObserverInterface
{
    /**
     * @var SynchronizationServiceInterface
     */
    private $synchronizationService;

    /**
     * SaveCustomer constructor.
     *
     * @param SynchronizationServiceInterface $synchronizationService
     */
    public function __construct(SynchronizationServiceInterface $synchronizationService)
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