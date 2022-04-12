<?php

namespace Amasty\IlyaRog\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\TestFramework\Event\Magento;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.0.3', '<'))
        {
            $setup->getConnection()->addColumn(
                $setup->getTable('Amasty_IlyaRog_blacklist'),
                'email_body',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'default' => '',
                    'comment' => 'Email Body'
                ]
            );
        }

        $setup->endSetup();
    }
}
