<?php

namespace Test\CleverreachPlugin\Model;

use Magento\Framework\Model\AbstractModel;
use Test\CleverreachPlugin\ResourceModel\SubscriberEntity;

class SubscriberModel extends AbstractModel
{
    public function __construct()
    {
        $this->_init(SubscriberEntity::class);
    }
}