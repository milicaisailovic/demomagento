<?php

namespace Test\CleverreachPlugin\Controller\Callback;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Test\CleverreachPlugin\Service\Authorization\AuthorizationService;

/**
 * Class Index
 */
class Index extends Action
{
    protected PageFactory $_pageFactory;

    /**
     * Callback index constructor.
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context     $context,
        PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * Get CleverReach code, send request to API for login and save token in database if token is valid.
     *
     * @return Page|ResultInterface|string
     */
    public function execute()
    {
        $authorizationService = new AuthorizationService();

        $response = $authorizationService->verify($_GET['code']);
        $responseDecoded = json_decode($response, true);

        if (array_key_exists('error', $responseDecoded)) {
            echo 'cUrl error: ' . $responseDecoded['error'];

            return '';
        }

        $authorizationService->set($responseDecoded['access_token']);

        return $this->_pageFactory->create();
    }
}