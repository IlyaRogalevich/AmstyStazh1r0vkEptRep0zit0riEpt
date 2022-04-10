<?php

namespace Amasty\SecondIlyaRog\Controller\Lesson2;

use Amasty\IlyaRog\Controller\Lesson2\Index as MainIndex;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Session;

class Index extends MainIndex
{
    /**
     * @var Session
     */
    private $checkoutSession;

    public function __construct(
        ResultFactory $resultFactory,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession

    ) {
        parent::__construct($resultFactory, $scopeConfig);
        $this->checkoutSession = $checkoutSession;
    }

    public function execute()
    {
        if($this->checkoutSession->isLoggedIn()){
            return parent::execute();
        } else {
            die('Нужна авторизация');
        }
    }
}
