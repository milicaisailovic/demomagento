<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\Template;

class CallbackBlock extends Template
{
    protected UrlInterface $url;

    public function __construct(
        Context   $context,
        UrlInterface $url,
        array     $data = []
    )
    {
        $this->url = $url;
        parent::__construct($context, $data);
    }

    public function getRedirectUrl(): string
    {
        return $this->url->getUrl('cleverreach/dashboard/index');
    }
}

