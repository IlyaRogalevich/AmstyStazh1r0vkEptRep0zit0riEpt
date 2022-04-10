<?php

namespace Amasty\IlyaRog\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Event\ManagerInterface;

class Form extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    const FORM_ACTION = ('IlyaRog/Cart/Add');

    public function __construct(Template\Context $context,
                                ScopeConfigInterface $scopeConfig,
                                array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function isShowQty()
    {
        return $this->scopeConfig->isSetFlag('test_config/general/show_qty');
    }

    public function getQtyStandart()
    {
        return $this->scopeConfig->getValue('test_config/general/standart_qty') ?: "0";
    }

    public function getFormAction()
    {
        return self::FORM_ACTION;
    }


}
