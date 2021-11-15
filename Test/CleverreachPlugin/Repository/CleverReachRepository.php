<?php

namespace Test\CleverreachPlugin\Repository;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Test\CleverreachPlugin\ResourceModel\CleverReachEntity;
use Test\CleverreachPlugin\Service\Authorization\DTO\AccessToken;
use Test\CleverreachPlugin\Service\Authorization\DTO\ClientInfo;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\DTO\CleverReachInformation;
use Test\CleverreachPlugin\Service\Synchronization\DTO\GroupInfo;

class CleverReachRepository
{
    const TABLE_NAME = 'cleverreach_entity';
    /**
     * @var CleverReachEntity
     */
    protected $resourceEntity;

    /**
     * CleverReachRepository constructor.
     */
    public function __construct()
    {
        $this->resourceEntity = ObjectManager::getInstance()->create(CleverReachEntity::class);
        $this->resourceEntity->setTableName(self::TABLE_NAME);
    }

    /**
     * Set information in database.
     *
     * @param CleverReachInformation $information
     */
    public function set(CleverReachInformation $information): void
    {
        try {
            $this->resourceEntity->upsert($information);
        } catch (LocalizedException $exception) {
            return;
        }
    }

    /**
     * Get information from database.
     *
     * @param string $name
     *
     * @return AccessToken|ClientInfo|GroupInfo|null
     */
    public function get(string $name)
    {
        try {
            $resource = $this->resourceEntity->select($name);
            if ($resource === null) {
                return null;
            }

            switch ($name) {
                case CleverReachConfig::ACCESS_TOKEN_NAME:
                    return new AccessToken(json_decode($resource['value'], true)['value']);
                case CleverReachConfig::CLIENT_INFO_NAME:
                    return new ClientInfo(json_decode($resource['value'], true)['id']);
                case CleverReachConfig::GROUP_INFO_NAME:
                    return new GroupInfo(json_decode($resource['value'], true)['id']);
                default:
                    return null;
            }
        } catch (LocalizedException $exception) {
            return null;
        }
    }
}
