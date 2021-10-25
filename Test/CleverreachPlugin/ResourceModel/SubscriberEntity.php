<?php

namespace Test\CleverreachPlugin\ResourceModel;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class SubscriberEntity extends AbstractDb
{
    const TABLE_NAME = 'newsletter_subscriber';

    private AdapterInterface $connection;
    private string $tableName;

    /**
     * SubscriberEntity constructor.
     */
    protected function _construct()
    {
        $this->connection = $this->getConnection();
        $this->tableName = $this->connection->getTableName(self::TABLE_NAME);
    }

    /**
     * Select subscribers from database with current limit parameters.
     *
     * @param int $groupNumber
     *
     * @return array
     */
    public function select(int $groupNumber): array
    {
        $query = $this->connection->select()->from($this->tableName)
            ->limitPage($groupNumber, CleverReachConfig::NUMBER_OF_RECEIVERS_IN_GROUP);

        return $this->connection->fetchAll($query);
    }

    /**
     * Return number of rows in subscribers table.
     *
     * @return int
     */
    public function count(): int
    {
        $query = $this->connection->select()->from($this->tableName, 'COUNT(*)');

        return (int)$this->connection->fetchOne($query);
    }
}