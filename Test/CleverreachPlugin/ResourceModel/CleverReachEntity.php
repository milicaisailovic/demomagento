<?php

namespace Test\CleverreachPlugin\ResourceModel;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CleverReachEntity extends AbstractDb
{
    const TABLE_NAME = 'cleverreach_entity';

    private AdapterInterface $connection;
    private string $tableName;

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'id');
        $this->connection = $this->getConnection();
        $this->tableName = $this->connection->getTableName(self::TABLE_NAME);
    }

    public function setToken(string $clientId, string $token) : void
    {
        $data = ['client_id' => $clientId, 'access_token' => $token];
        if($this->getToken($clientId) !== null) {
            $this->connection->update($this->tableName, $data, [self::TABLE_NAME . '.client_id = ?' => $clientId]);
        } else {
            $this->connection->insert($this->tableName, $data);
        }
    }

    public function getToken(string $clientId) : ?string
    {
        $select = $this->connection->select()->from($this->tableName, 'access_token')
            ->where(self::TABLE_NAME . '.client_id = ?', $clientId);
        $result = $this->connection->fetchRow($select);

        return !empty($result) ? (string) $result['access_token'] : null;
    }
}