<?php
namespace Test\CleverreachPlugin\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
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

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     *
     * @return Page
     */
    public function execute() : Page
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(CleverReachConfig::MENU_ID);
        $resultPage->getConfig()->getTitle()->prepend(__(''));

        return $resultPage;
    }
}
