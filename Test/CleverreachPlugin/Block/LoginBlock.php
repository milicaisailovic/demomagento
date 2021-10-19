<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Test\CleverreachPlugin\Controller\Adminhtml\Callback\Index;
use Test\CleverreachPlugin\Service\AuthorizationService;

class LoginBlock extends Template
{
    const CLIENT_ID = 'pKGERcfgnp';
    const CLIENT_SECRET = 'tq2UcU9wnn8i9sRRLfQi5Mg78lDgMBFN';

    const AUTHORIZE_URL = 'https://rest.cleverreach.com/oauth/authorize.php';
    const SITE_URL = 'http://magento24.test';

    protected UrlInterface $_urlInterface;

    public function __construct(
        Context $context,
        UrlInterface $urlInterface,
        array $data = []
    )
    {
        $this->_urlInterface = $urlInterface;
        parent::__construct($context, $data);
    }

    public function getLoginPageUrl() : string
    {
        $authorizationService = new AuthorizationService();
        $redirectUri = $authorizationService->getRedirectUri(self::SITE_URL, Index::class);

        return self::AUTHORIZE_URL . '?client_id=' . self::CLIENT_ID
            . '&grant=basic&response_type=code&redirect_uri=' . urlencode($redirectUri);

    }
}