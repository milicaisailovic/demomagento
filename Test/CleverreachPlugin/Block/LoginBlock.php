<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Test\CleverreachPlugin\Controller\Callback\Index;
use Test\CleverreachPlugin\Service\Authorization\AuthorizationService;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class LoginBlock extends Template
{
    public function __construct(
        Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    public function getLoginPageUrl() : string
    {
        $authorizationService = new AuthorizationService();
        $redirectUri = $authorizationService->getRedirectUri(CleverReachConfig::SITE_URL, Index::class);

        return CleverReachConfig::AUTHORIZE_URL . '?client_id=' . CleverReachConfig::CLIENT_ID
            . '&grant=basic&response_type=code&redirect_uri=' . urlencode($redirectUri);
    }

    public function checkLoginUrl() : string
    {
        return $this->getUrl('cleverreach/login/checklogin');
    }

    public function redirectToDashboard() : string
    {
        return $this->getUrl('cleverreach/dashboard/index');
    }
}