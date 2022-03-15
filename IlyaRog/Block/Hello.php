<?php

namespace Amasty\IlyaRog\Block;

use Magento\Framework\View\Element\Template;

class Hello extends Template
{
    public function sayHi()
    {
        return "hello world";
    }
}
