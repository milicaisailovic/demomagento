<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;

class CallbackBlock extends Template
{
    /**
     * CallbackBlock constructor.
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    /**
     * Get URL for dashboard page.
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->getUrl('cleverreach/dashboard/index');
    }
}

