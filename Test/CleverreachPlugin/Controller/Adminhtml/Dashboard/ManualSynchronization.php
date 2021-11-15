<?php

namespace Test\CleverreachPlugin\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Test\CleverreachPlugin\Service\Synchronization\Contracts\SynchronizationServiceInterface;
use Test\CleverreachPlugin\Service\Synchronization\Exceptions\SynchronizationException;

class ManualSynchronization extends Action implements HttpGetActionInterface
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var SynchronizationServiceInterface
     */
    private $synchronizationService;

    /**
     * Synchronization constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param SynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        Context                         $context,
        JsonFactory                     $resultJsonFactory,
        SynchronizationServiceInterface $synchronizationService
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
        try {
            $this->synchronizationService->synchronize();
        } catch (SynchronizationException $e) {
            $response->setHttpResponseCode($e->getCode());
        }

        return $response;
    }
}
