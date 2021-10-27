<?php

namespace Test\CleverreachPlugin\Service\Authorization;

use Magento\Framework\App\ObjectManager;
use Test\CleverreachPlugin\Http\Proxy;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class AuthorizationProxy extends Proxy
{
    /**
     * Send HTTP POST request to API for logging in.
     *
     * @param string $code
     *
     * @return string
     */
    public function verify(string $code): string
    {
        $objectManager = ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        $fields["redirect_uri"] = $storeManager->getStore()->getBaseUrl() . 'front/callback/index';
        $fields["client_id"] = CleverReachConfig::CLIENT_ID;
        $fields["client_secret"] = CleverReachConfig::CLIENT_SECRET;
        $fields["grant_type"] = "authorization_code";
        $fields["code"] = $code;

        return $this->post(CleverReachConfig::TOKEN_URL, $fields);
    }

    /**
     * Send HTTP GET request for client information.
     *
     * @param string $token
     *
     * @return string
     */
    public function getClientAccountInformation(string $token): string
    {
        return $this->get(CleverReachConfig::DEBUG_URL . '?token=' . $token);
    }
}