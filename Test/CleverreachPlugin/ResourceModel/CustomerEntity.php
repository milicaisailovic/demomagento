<?php

namespace Test\CleverreachPlugin\ResourceModel;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class CustomerEntity extends AbstractDb
{
    const TABLE_NAME = 'customer_entity';

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @var string
     */
    private $tableName;

    /**
     * CustomerEntity constructor.
     */
    protected function _construct()
    {
        $this->connection = $this->getConnection();
        $this->tableName = $this->connection->getTableName(self::TABLE_NAME);
    }

    /**
     * Select customers from database with current limit parameters.
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
     * @param string $email
     *
     * @return array
     */
    public function selectByEmail(string $email): array
    {
        $query = $this->connection->select()->from($this->tableName)->where('email = ?', $email);

        return $this->connection->fetchRow($query);
    }

    /**
     * Insert customer into database with forwarded fields.
     *
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     */
    public function upsert(string $email, string $firstname, string $lastname): void
    {
        $data = ['email' => $email, 'firstname' => $firstname, 'lastname' => $lastname];
        $this->connection->insertOnDuplicate($this->tableName, $data);
    }

    /**
     * For forwarded customer ID, update row in database.
     *
     * @param int $id
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     */
    public function update(int $id, string $email, string $firstname, string $lastname): void
    {
        $data = ['email' => $email, 'firstname' => $firstname, 'lastname' => $lastname];
        $this->connection->update($this->tableName, $data, ['id = ?' => $id]);
    }

    /**
     * Return number of rows in customer table.
     *
     * @return int
     */
    public function count(): int
    {
        $query = $this->connection->select()->from($this->tableName, 'COUNT(*)');

        return (int)$this->connection->fetchOne($query);
    }
}