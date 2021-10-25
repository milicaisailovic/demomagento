<?php

namespace Test\CleverreachPlugin\Model;

use Magento\Framework\Model\AbstractModel;
use Test\CleverreachPlugin\ResourceModel\CustomerEntity;

class CustomerModel extends AbstractModel
{
    public function __construct()
    {
        $this->_init(CustomerEntity::class);
    }

}