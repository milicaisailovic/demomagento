<?php

namespace Test\CleverreachPlugin\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\Exceptions\SynchronizationException;
use Test\CleverreachPlugin\Service\Synchronization\SynchronizationService;

class Synchronization extends Action implements HttpGetActionInterface
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var SynchronizationService
     */
    private $synchronizationService;

    /**
     * Synchronization constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param SynchronizationService $synchronizationService
     */
    public function __construct(
        Context                $context,
        JsonFactory            $resultJsonFactory,
        SynchronizationService $synchronizationService
    )
    {
        parent::__construct($context);
        $this->synchronizationService = $synchronizationService;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Initial synchronization after login.
     *
     * @return Json
     */
    public function execute(): Json
    {
        $response = $this->resultJsonFactory->create();
        $this->synchronizationService->createGroup('demomagento2.3');
        $this->synchronizationService->getGroupInfo();
        $numberOfReceivers = $this->synchronizationService->getNumberOfReceivers();

        $customerGroups = ceil($numberOfReceivers['customer'] / CleverReachConfig::NUMBER_OF_RECEIVERS_IN_GROUP);
        $subscriberGroups = ceil($numberOfReceivers['subscriber'] / CleverReachConfig::NUMBER_OF_RECEIVERS_IN_GROUP);

        try {
            $this->synchronizationService->synchronizeReceivers($customerGroups, 'customer');
            $this->synchronizationService->synchronizeReceivers($subscriberGroups, 'subscriber');

            return $response;
        } catch (SynchronizationException $e) {
            $response->setHttpResponseCode($e->getCode());

            return $response;
        }
    }
}
