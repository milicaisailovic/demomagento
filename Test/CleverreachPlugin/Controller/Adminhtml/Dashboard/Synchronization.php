<?php

namespace Test\CleverreachPlugin\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Test\CleverreachPlugin\Http\ApiProxy;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\Exceptions\SynchronizationException;
use Test\CleverreachPlugin\Service\Synchronization\SynchronizationService;

class Synchronization extends Action implements HttpGetActionInterface
{
    protected JsonFactory $resultJsonFactory;

    private ApiProxy $apiProxy;
    private SynchronizationService $synchronizationService;

    /**
     * Synchronization constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context     $context,
        JsonFactory $resultJsonFactory
    )
    {
        parent::__construct($context);
        $this->apiProxy = new ApiProxy();
        $this->synchronizationService = new SynchronizationService();
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
        $groupInfoSerialized = $this->apiProxy->createGroup('demomagento');
        $this->synchronizationService->setGroupInfo($groupInfoSerialized);
        $numberOfReceivers = $this->synchronizationService->getNumberOfReceivers();

        $customerGroups = ceil($numberOfReceivers['customer'] / CleverReachConfig::NUMBER_OF_RECEIVERS_IN_GROUP);
        $subscriberGroups = ceil($numberOfReceivers['subscriber'] / CleverReachConfig::NUMBER_OF_RECEIVERS_IN_GROUP);

        try {
            $this->synchronizeReceivers($subscriberGroups, 'subscriber');
            $this->synchronizeReceivers($customerGroups, 'customer');

            return $response;
        } catch (SynchronizationException $e) {
            $response->setHttpResponseCode($e->getCode());

            return $response;
        }
    }

    /**
     * Get receivers from database and send them to API.
     *
     * @param int $numberOfGroups
     * @param string $type
     *
     * @throws SynchronizationException
     */
    private function synchronizeReceivers(int $numberOfGroups, string $type): void
    {
        for ($i = 1; $i <= $numberOfGroups; $i++) {
            $receivers = ($type === 'customer') ? $this->synchronizationService->getReceiversFromCustomers($i) :
                $this->synchronizationService->getReceiversFromSubscribers($i);
            $response = $this->apiProxy->sendReceivers($receivers);
            if (isset($response['error'])) {
                throw new SynchronizationException($response[''], $response['status']);
            }
        }
    }
}
