<?php

namespace Test\CleverreachPlugin\Controller\Adminhtml\Login;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Test\CleverreachPlugin\Service\Authorization\Contracts\AuthorizationServiceInterface;

/**
 * Class Index
 */
class Checklogin extends Action implements HttpGetActionInterface
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
        Context              $context,
        PageFactory          $resultPageFactory,
        AuthorizationServiceInterface $authorizationService
    )
    {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->authorizationService = $authorizationService;
    }

    /**
     * Check if token exists in database.
     *
     * @return void
     */
    public function execute()
    {
        $token = $this->authorizationService->get();
        if($token === null){
            echo 0;
        } else {
            echo json_encode([$this->getUrl('cleverreach/dashboard/index')]);
        }
    }
}
