<?php

namespace Amasty\IlyaRog\Controller\ForAjaxSku;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Checkout\Model\Session;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class AjaxCollection implements ActionInterface
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
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    public function __construct(
        ResultFactory              $resultFactory,
        ScopeConfigInterface       $scopeConfig,
        Session                    $checkoutSession,
        ProductRepositoryInterface $productRepository,
        CollectionFactory          $collectionFactory,
        RequestInterface           $request,
        ManagerInterface           $messageManager,
        JsonFactory                $jsonFactory
    )
    {
        $this->resultFactory = $resultFactory;
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->collectionFactory = $collectionFactory;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        $params = $this->request->getParams();
        $response = [];

        $collection = $this->collectionFactory->create()
            ->addAttributeToFilter('sku', ['like' => "%" . $params['sku'] . "%"])
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->setPageSize(5);
        foreach ($collection as $product) {
            $product = $product->getData('sku') . " " . $product->getData('name');
            $response[] = $product;
        }
        $result = $this->jsonFactory->create();
        return $result->setData($response);
    }
}
