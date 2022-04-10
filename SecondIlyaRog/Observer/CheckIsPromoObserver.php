<?php

namespace Amasty\SecondIlyaRog\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CheckIsPromoObserver implements ObserverInterface
{

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        ScopeConfigInterface       $scopeConfig,
        Session                    $checkoutSession,
        ProductRepositoryInterface $productRepository,
        RequestInterface           $request
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->request = $request;
    }

    public function execute(Observer $observer)
    {
        $promosku = $this->scopeConfig->getValue('for_sku_config/general/promoskuid');
        $forsku = $this->scopeConfig->getValue('for_sku_config/general/forskuid');
        $parameters = $this->request->getParams();
        $quote = $this->checkoutSession->getQuote();

        if ($parameters['sku'] == $forsku) {

            $product = $this->productRepository->get($promosku);
            if ($product->getTypeId() == 'simple') {
                $quote->addProduct($product, '1');
                $quote->save();
            }
        }
    }
}
