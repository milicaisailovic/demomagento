<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class DashboardBlock extends Template
{
    /**
     * DashboardBlock constructor.
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array   $data = []
    )
    {
        parent::__construct($context, $data);
    }

    /**
     * Return client ID.
     *
     * @return string
     */
    public function getClientId(): string
    {
        return CleverReachConfig::CLIENT_ID;
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