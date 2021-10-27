<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\Synchronization\SynchronizationService;

class DashboardBlock extends Template
{
    private SynchronizationService $synchronizationService;

    /**
     * DashboardBlock constructor.
     *
     * @param Context $context
     * @param SynchronizationService $synchronizationService
     * @param array $data
     */
    public function __construct(
        Context $context,
        SynchronizationService $synchronizationService,
        array   $data = []
    )
    {
        parent::__construct($context, $data);
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * Return client ID.
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->synchronizationService->getClientId();
    }

    /**
     * Get URL for starting initial synchronization.
     *
     * @return string
     */
    public function getSynchronizationUrl(): string
    {
        return $this->getUrl('cleverreach/dashboard/synchronization');
    }
}