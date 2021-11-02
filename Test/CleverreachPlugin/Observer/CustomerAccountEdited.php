<?php

namespace Test\CleverreachPlugin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Test\CleverreachPlugin\Service\Synchronization\Contracts\SynchronizationServiceInterface;

class CustomerAccountEdited implements ObserverInterface
{
    /**
     * @var SynchronizationServiceInterface
     */
    private $synchronizationService;

    /**
     * CustomerAccountEdited constructor.
     */
    public function __construct(
        SynchronizationServiceInterface $synchronizationService
    )
    {
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * Edit receiver on API when customer updates his information.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $email = $observer->getEvent()->getEmail();
        $this->synchronizationService->sendEditedInformation($email);
    }
}