<?php

namespace Test\CleverreachPlugin\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Test\CleverreachPlugin\Service\Authorization\Contracts\AuthorizationServiceInterface;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class LoginBlock extends Template
{
    /**
     * @var AuthorizationServiceInterface
     */
    private $authorizationService;

    /**
     * LoginBlock constructor.
     *
     * @param Context $context
     * @param AuthorizationServiceInterface $authorizationService
     * @param array $data
     */
    public function __construct(
        Context                       $context,
        AuthorizationServiceInterface $authorizationService,
        array                         $data = []
    )
    {
        parent::__construct($context, $data);
        $this->authorizationService = $authorizationService;
    }

    /**
     * Return login page URL.
     *
     * @return string
     */
    public function getLoginPageUrl(): string
    {
        $redirectUri = $this->authorizationService->getRedirectUri();

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
}