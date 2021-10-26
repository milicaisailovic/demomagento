<?php

namespace Test\CleverreachPlugin\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Create cleverreach entity database table
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('cleverreach_entity');
        if ($setup->getConnection()->isTableExists($tableName) !== true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    [
                        'default' => null
                    ]
                )->addIndex(
                    $setup->getIdxName(
                        $setup->getTable($tableName),
                        ['name'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['name'],
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )->addColumn(
                    'value',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'default' => null
                    ]
                )->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');

            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}