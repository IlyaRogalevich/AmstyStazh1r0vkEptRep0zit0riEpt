<?php

namespace Amasty\SecondIlyaRog\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;

class ChangeMagentoAddCart
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ){
        $this->productRepository = $productRepository;
    }

    public function beforeExecute(
        \Magento\Checkout\Controller\Cart\Add $subject
    ) {
        $params = $subject->getRequest()->getParams();
        if(!isset($params['product'])) {
            $subject->getRequest()->setPostValue('product', $this->productRepository->get($params['sku'])->getId());
        }
    }
}
