<?php

namespace Test\CleverreachPlugin\Controller\Adminhtml\Login;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Test\CleverreachPlugin\Service\Authorization\AuthorizationService;

/**
 * Class Index
 */
class Checklogin extends Action implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context     $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Check if token exists in database.
     *
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $authorizationService = new AuthorizationService();
        $token = $authorizationService->get();

        echo $token !== null;
    }
}
