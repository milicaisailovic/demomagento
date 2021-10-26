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
}