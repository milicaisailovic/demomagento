<?php

namespace Test\CleverreachPlugin\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Test\CleverreachPlugin\Service\DTO\CleverReachInformation;

class CleverReachEntity extends AbstractDb
{
    /**
     * CleverReachEntity constructor.
     */
    protected function _construct()
    {
    }

    /**
     * Set resource model table name.
     *
     * @param string $tableName Name of the database table.
     */
    public function setTableName(string $tableName)
    {
        $this->_init($tableName, 'id');
    }

    /**
     * Select value for forwarded name
     *
     * @param string $name
     *
     * @return array|null
     * @throws LocalizedException
     */
    public function select(string $name): ?array
    {
        $select = $this->getConnection()->select()->from($this->getMainTable())
            ->where($this->getMainTable() . '.name = ?', $name);
        $result = $this->getConnection()->fetchRow($select);

        return !empty($result) ? $result : null;
    }

    /**
     * Insert value for forwarded name.
     *
     * @param CleverReachInformation $information
     *
     * @throws LocalizedException
     */
    public function upsert(CleverReachInformation $information): void
    {
        $data = ['name' => $information->getName(), 'value' => json_encode($information->toArray())];
        $this->getConnection()->insertOnDuplicate($this->getMainTable(), $data);
    }

    /**
     * Update value for forwarded name.
     *
     * @param CleverReachInformation $information
     *
     * @throws LocalizedException
     */
    public function update(CleverReachInformation $information): void
    {
        $data = ['value' => json_encode($information->toArray())];
        $this->getConnection()->update($this->getMainTable(), $data, ['name = ?' => $information->getName()]);
    }
}
