<?php

namespace Amasty\IlyaRog\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;

class Hello extends Template
{
    /**
     * @var ScopeConfigInterface
     */

    private $scopeConfig;

    public function __construct(Template\Context $context,
                                ScopeConfigInterface $scopeConfig,
                                array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function sayHi()
    {
        return "hello world";
    }

    public function getWelcomeText()
    {
        return $this->scopeConfig->getValue('test_config/general/welcome_text') ?: '';
    }

    public function qtyShow()
    {
        return $this->scopeConfig->getValue('test_config/general/show_qty') ?: '';
    }
}
