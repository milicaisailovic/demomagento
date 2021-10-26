<?php

namespace Test\CleverreachPlugin\Controller\Adminhtml\Login;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Test\CleverreachPlugin\Service\Authorization\AuthorizationService;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

/**
 * Class Index
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    private AuthorizationService $authorizationService;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param AuthorizationService $authorizationService
     */
    public function __construct(
        Context              $context,
        PageFactory          $resultPageFactory,
        AuthorizationService $authorizationService
    )
    {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->authorizationService = $authorizationService;
    }

    /**
     * Render CleverReach landing page if token doesn't exists.
     *
     * @return Page
     */
    public function execute(): Page
    {
        $resultPage = $this->resultPageFactory->create();
        $token = $this->authorizationService->get();

        if ($token !== null) {
            $this->_redirect('cleverreach/dashboard/index');
        }

        $resultPage->getConfig()->getTitle()->prepend(__(''));
        $resultPage->setActiveMenu(CleverReachConfig::MENU_ID);

        return $resultPage;
    }
}
