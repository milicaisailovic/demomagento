<?php

namespace Test\CleverreachPlugin\Controller\Callback;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Test\CleverreachPlugin\Service\Authorization\Contracts\AuthorizationServiceInterface;
use Test\CleverreachPlugin\Service\Authorization\Exceptions\AuthorizationException;

/**
 * Class Index
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * @var AuthorizationServiceInterface
     */
    private $authorizationService;

    /**
     * Callback index constructor.
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param AuthorizationServiceInterface $authorizationService
     */
    public function __construct(
        Context                       $context,
        PageFactory                   $pageFactory,
        AuthorizationServiceInterface $authorizationService
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
        try {
            $this->authorizationService->verify($_GET['code']);

            return $this->_pageFactory->create();

        } catch (AuthorizationException $exception) {
            echo $exception->getMessage();

            return '';
        }
    }
}