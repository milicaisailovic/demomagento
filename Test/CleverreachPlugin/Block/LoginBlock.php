<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Test\CleverreachPlugin\Service\Authorization\AuthorizationService;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class LoginBlock extends Template
{
    /**
     * LoginBlock constructor.
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
     * Return login page URL.
     *
     * @return string
     */
    public function getLoginPageUrl(): string
    {
        $authorizationService = new AuthorizationService();
        $redirectUri = $authorizationService->getRedirectUri();

        return CleverReachConfig::AUTHORIZE_URL . '?client_id=' . CleverReachConfig::CLIENT_ID
            . '&grant=basic&response_type=code&redirect_uri=' . urlencode($redirectUri);
    }

    /**
     * Get URL for checking if login is done.
     *
     * @return string
     */
    public function checkLoginUrl(): string
    {
        return $this->getUrl('cleverreach/login/checklogin');
    }

    /**
     * Get URL for dashboard.
     *
     * @return string
     */
    public function redirectToDashboard(): string
    {
        return $this->getUrl('cleverreach/dashboard/index');
    }
}