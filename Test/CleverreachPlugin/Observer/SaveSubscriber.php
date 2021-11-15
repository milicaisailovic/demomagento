<?php

namespace Test\CleverreachPlugin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Test\CleverreachPlugin\Service\Synchronization\Contracts\SynchronizationServiceInterface;
use Test\CleverreachPlugin\Service\Synchronization\DTO\Receiver;

class SaveSubscriber implements ObserverInterface
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
        $subscriber = $observer->getEvent()->getSubscriber();
        $deactivated = 0;
        if ($subscriber->getSubscriberStatus() === 3) {
            $deactivated = strtotime($subscriber->getChangeStatusAt());
        }
        $receiver = new Receiver($subscriber->getId(), $subscriber->getEmail(),
            strtotime($subscriber->getChangeStatusAt()), $deactivated);
        $this->synchronizationService->sendReceivers([$receiver]);
    }
}
