<?php

namespace Test\CleverreachPlugin\ResourceModel\CleverReachEntity;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Test\CleverreachPlugin\Model\CleverReachModel;
use Test\CleverreachPlugin\ResourceModel\CleverReachEntity;

class Collection extends AbstractCollection
{
    /**
     * Collection initialization.
     */
    protected function _construct()
    {
        $this->_init(CleverReachModel::class, CleverReachEntity::class);
    }
}