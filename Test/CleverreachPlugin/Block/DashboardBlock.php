<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Test\CleverreachPlugin\Service\Synchronization\Contracts\SynchronizationServiceInterface;

class DashboardBlock extends Template
{
    /**
     * @var SynchronizationServiceInterface
     */
    private $synchronizationService;

    /**
     * DashboardBlock constructor.
     *
     * @param Context $context
     * @param SynchronizationServiceInterface $synchronizationService
     * @param array $data
     */
    public function __construct(
        Context                         $context,
        SynchronizationServiceInterface $synchronizationService,
        array                           $data = []
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

    /**
     * Get URL for manual synchronization.
     *
     * @return string
     */
    public function getManualSynchronizationUrl(): string
    {
        return $this->getUrl('cleverreach/dashboard/manualsynchronization');

    }
}