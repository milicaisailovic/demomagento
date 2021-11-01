<?php

namespace Test\CleverreachPlugin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Test\CleverreachPlugin\Service\Synchronization\SynchronizationService;

class CustomerAccountEdited implements ObserverInterface
{
    /**
     * @var SynchronizationService
     */
    private $synchronizationService;

    /**
     * CustomerAccountEdited constructor.
     */
    public function __construct(
        SynchronizationService $synchronizationService
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