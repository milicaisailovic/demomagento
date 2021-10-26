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

    private AuthorizationService $authorizationService;

    /**
     * Callback index constructor.
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context              $context,
        PageFactory          $pageFactory,
        AuthorizationService $authorizationService
    )
    {
        parent::__construct($context);
        $this->_pageFactory = $pageFactory;
        $this->authorizationService = $authorizationService;
    }

    /**
     * Get CleverReach code, send request to API for login and save token in database if token is valid.
     *
     * @return Page|ResultInterface|string
     */
    public function execute()
    {
        $response = $this->authorizationService->verify($_GET['code']);
        $responseDecoded = json_decode($response, true);

        if (array_key_exists('error', $responseDecoded)) {
            echo 'cUrl error: ' . $responseDecoded['error'];

            return '';
        }

        $this->authorizationService->set($responseDecoded['access_token']);

        return $this->_pageFactory->create();
    }
}