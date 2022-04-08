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
        $sku = $_POST['sku'];
        $response = [];
        $result = $this->jsonFactory->create();

        $collection = $this->collectionFactory->create();
        $collection->addAttributeToFilter('sku', ['like' => "%" . $sku . "%"]);
        $collection->addAttributeToSelect( 'name');
        $collection->addAttributeToSelect('sku');

        $i=0;

        foreach ($collection as $product) {
            $i++;
            if ($i < 6){
                $product2 = [$product->getData('sku') . " " . $product->getData('name')];
                $response = array_merge($response, $product2);
            }
        }

        return $result->setData($response);
    }
}
