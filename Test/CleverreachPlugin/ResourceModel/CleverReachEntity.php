<?php

namespace Test\CleverreachPlugin\ResourceModel;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Test\CleverreachPlugin\Service\DataModel\CleverReachInformation;

class CleverReachEntity extends AbstractDb
{
    const TABLE_NAME = 'cleverreach_entity';

    private AdapterInterface $connection;
    private string $tableName;

    /**
     * CleverReachEntity constructor.
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'id');
        $this->connection = $this->getConnection();
        $this->tableName = $this->connection->getTableName(self::TABLE_NAME);
    }

    /**
     * Select value for forwarded name
     *
     * @param string $name
     *
     * @return array|null
     */
    public function select(string $name): ?array
    {
        $select = $this->connection->select()->from($this->tableName)
            ->where(self::TABLE_NAME . '.name = ?', $name);
        $result = $this->connection->fetchRow($select);

        return !empty($result) ? $result : null;
    }

    /**
     * Insert value for forwarded name.
     *
     * @param CleverReachInformation $information
     */
    public function upsert(CleverReachInformation $information): void
    {
        $data = ['name' => $information->getName(), 'value' => $information->getValue()];
        $this->connection->insertOnDuplicate($this->tableName, $data);
    }

    /**
     * Update value for forwarded name.
     *
     * @param CleverReachInformation $information
     */
    public function update(CleverReachInformation $information): void
    {
        $data = ['value' => $information->getValue()];
        $this->connection->update($this->tableName, $data, ['name = ?' => $information->getName()]);
    }
}
