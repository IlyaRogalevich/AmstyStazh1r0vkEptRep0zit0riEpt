<?php

namespace Amasty\IlyaRog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Blacklist extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(
            'Amasty_IlyaRog_blacklist',
            'id'
        );
    }
}
