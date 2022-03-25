<?php

namespace Amasty\IlyaRog\Controller\Check;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Checkout\Model\Session;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;

class Check implements ActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

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
        ResultFactory              $resultFactory,
        ScopeConfigInterface       $scopeConfig,
        Session                    $checkoutSession,
        ProductRepositoryInterface $productRepository,
        RequestInterface           $request
    )
    {
        $this->resultFactory = $resultFactory;
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->request = $request;
    }

    public function execute()
    {
        $parameters = $this->request->getParams();
        $quote = $this->checkoutSession->getQuote();

        try {
            if (!$quote->getId()) {
                $quote->save();
            }

            $product = $this->productRepository->get($parameters['sku']);
            if ($product->getTypeId() == 'simple'){
                $quote->addProduct($product, $parameters['qty']);
                $quote->save();
                die('Товар добавлен');
            } else {
                die('Товар не simple');
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            die('Такого продукта не существует');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            die('Товара недостаточно');
        }
    }
}
