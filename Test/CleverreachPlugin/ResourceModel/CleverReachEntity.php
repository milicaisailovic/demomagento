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

    /**
     * Select value for forwarded name
     *
     * @param string $field
     *
     * @return string|null
     */
    public function select(string $field): ?string
    {
        $select = $this->connection->select()->from($this->tableName, 'value')
            ->where(self::TABLE_NAME . '.name = ?', $field);
        $result = $this->connection->fetchOne($select);

        return !empty($result) ? $result : null;
    }

    /**
     * Insert value for forwarded name
     *
     * @param string $name
     * @param string $value
     */
    public function insert(string $name, string $value): void
    {
        $data = ['name' => $name, 'value' => $value];
        $this->connection->insertOnDuplicate($this->tableName, $data);
    }
}
