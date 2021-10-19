<?php

namespace Test\CleverreachPlugin\Controller\Callback;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Test\CleverreachPlugin\Http\AuthorizationProxy;
use Test\CleverreachPlugin\Service\AuthorizationService;

/**
 * Class Index
 */

class Index extends \Magento\Framework\App\Action\Action
{
    const CLIENT_ID = 'dummyId';
    const CLIENT_SECRET = 'dummySecret';

    const AUTHORIZE_URL = 'https://rest.cleverreach.com/oauth/authorize.php';
    const SITE_URL = 'http://magento24.test';

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

        $redirectUri = $authorizationService->getRedirectUri(self::SITE_URL, Index::class);
        $response = $authorizationProxy->verify(self::CLIENT_ID, self::CLIENT_SECRET, $_GET['code'], $redirectUri);
        $responseDecoded = json_decode($response, true);

        if(array_key_exists('access_token', $responseDecoded)) {
            $authorizationService->set(self::CLIENT_ID, $responseDecoded['access_token']);
        } elseif(array_key_exists('error', $responseDecoded)) {
            echo 'cUrl error: ' . $responseDecoded['error'];
        }
    }
}