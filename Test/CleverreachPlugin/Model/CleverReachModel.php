<?php

namespace Test\CleverreachPlugin\Model;

use Magento\Framework\Model\AbstractModel;
use Test\CleverreachPlugin\ResourceModel\CleverReachEntity;

class CleverReachModel extends AbstractModel
{
    public function __construct()
    {
        $this->_init(CleverReachEntity::class);
    }
}