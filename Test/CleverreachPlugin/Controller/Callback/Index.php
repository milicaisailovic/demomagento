<?php

namespace Test\CleverreachPlugin\Controller\Callback;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Test\CleverreachPlugin\Http\AuthorizationProxy;
use Test\CleverreachPlugin\Service\Authorization\AuthorizationService;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

/**
 * Class Index
 */

class Index extends \Magento\Framework\App\Action\Action
{
    protected PageFactory $_pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $authorizationService = new AuthorizationService();
        $authorizationProxy = new AuthorizationProxy();

        $redirectUri = $authorizationService->getRedirectUri(CleverReachConfig::SITE_URL, Index::class);
        $response = $authorizationProxy->verify(CleverReachConfig::CLIENT_ID,
            CleverReachConfig::CLIENT_SECRET, $_GET['code'], $redirectUri);
        $responseDecoded = json_decode($response, true);

        if(array_key_exists('access_token', $responseDecoded)) {
            $authorizationService->set(CleverReachConfig::CLIENT_ID, $responseDecoded['access_token']);

            return $this->_pageFactory->create();

        } elseif(array_key_exists('error', $responseDecoded)) {
            echo 'cUrl error: ' . $responseDecoded['error'];
        }

        return '';
    }
}