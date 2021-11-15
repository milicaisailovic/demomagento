<?php

namespace Test\CleverreachPlugin\Controller\Adminhtml\Login;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Test\CleverreachPlugin\Service\Authorization\Contracts\AuthorizationServiceInterface;

/**
 * Class Index
 */
class Checklogin extends Action implements HttpGetActionInterface
{

    /**
     * @var JsonFactory
     */
    protected $jsonResponseFactory;

    /**
     * @var AuthorizationServiceInterface
     */
    private $authorizationService;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param AuthorizationServiceInterface $authorizationService
     */
    public function __construct(
        Context                       $context,
        JsonFactory                   $jsonFactory,
        AuthorizationServiceInterface $authorizationService
    )
    {
        parent::__construct($context);

        $this->jsonResponseFactory = $jsonFactory;
        $this->authorizationService = $authorizationService;
    }

    /**
     * Check if token exists in database.
     *
     * @return Json
     */
    public function execute(): Json
    {
        $response = $this->jsonResponseFactory->create();
        $token = $this->authorizationService->get();
        if ($token === null) {
            return $response->setData([]);
        }

        return $response->setData([$this->getUrl('cleverreach/dashboard/index')]);
    }
}
