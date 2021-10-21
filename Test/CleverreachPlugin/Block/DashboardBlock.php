<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class DashboardBlock extends Template
{
    public function __construct(
        Context   $context,
        array     $data = []
    )
    {
        parent::__construct($context, $data);
    }

    public function getClientId() : string
    {
        return CleverReachConfig::CLIENT_ID;
    }
}