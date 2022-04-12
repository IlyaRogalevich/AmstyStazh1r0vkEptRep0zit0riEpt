<?php

namespace Amasty\IlyaRog\Model;

use Magento\Framework\Model\AbstractModel;

class Blacklist extends AbstractModel
{
    protected function _construct()
{
    $this->_init(\Amasty\IlyaRog\Model\ResourceModel\Blacklist::class);
}
}
