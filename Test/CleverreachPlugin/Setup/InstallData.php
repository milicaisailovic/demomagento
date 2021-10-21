<?php

namespace Test\CleverreachPlugin\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('cleverreach_entity');
        if($setup->getConnection()->isTableExists($tableName) === true)
        {
            $data = ['client_id' => null, 'access_token' => null];
            $setup->getConnection()->insert($tableName, $data);
        }

        $setup->endSetup();
    }
}