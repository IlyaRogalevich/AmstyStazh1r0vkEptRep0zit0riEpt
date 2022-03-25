<?php
namespace Amasty\IlyaRog\Controller\Proverka;
class HelloMagento extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        print_r('Привет Magento. Привет Amasty. Я готов тебя покорить!');
    }
}
