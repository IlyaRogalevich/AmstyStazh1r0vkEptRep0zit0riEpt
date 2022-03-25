<?php

namespace Amasty\IlyaRog\Controller\Proverka;

use Magento\Framework\App\ActionInterface;

class HelloMagento implements ActionInterface
{
    public function execute()
    {
        die('Привет Magento. Привет Amasty. Я готов тебя покорить!');
    }
}
