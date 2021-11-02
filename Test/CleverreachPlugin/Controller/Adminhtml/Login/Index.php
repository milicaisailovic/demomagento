<?php

namespace Test\CleverreachPlugin\Controller\Adminhtml\Login;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Test\CleverreachPlugin\Service\Authorization\Contracts\AuthorizationServiceInterface;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

/**
 * Class Index
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var AuthorizationServiceInterface
     */
    private $authorizationService;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param AuthorizationServiceInterface $authorizationService
     */
    public function __construct(
        Context                       $context,
        PageFactory                   $resultPageFactory,
        AuthorizationServiceInterface $authorizationService
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

        $resultPage->setActiveMenu(CleverReachConfig::MENU_ID);

        return $resultPage;
    }
}
