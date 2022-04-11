<?php

namespace Amasty\IlyaRog\Controller\Cart;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Checkout\Model\Session;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Amasty\IlyaRog\Model\BlacklistFactory;
use Amasty\IlyaRog\Model\ResourceModel\Blacklist;

class Add implements ActionInterface
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

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var BlacklistFactory
     */
    private $blacklistFactory;

    /**
     * @var Blacklist
     */
    private $blacklistResource;

    public function __construct(
        ResultFactory                             $resultFactory,
        ScopeConfigInterface                      $scopeConfig,
        Session                                   $checkoutSession,
        ProductRepositoryInterface                $productRepository,
        RequestInterface                          $request,
        EventManager                              $eventManager,
        ManagerInterface                          $messageManager,
        BlacklistFactory                          $blacklistFactory,
        Blacklist                                 $blacklistResource
    )
    {
        $this->resultFactory = $resultFactory;
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->eventManager = $eventManager;
        $this->messageManager = $messageManager;

        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistResource = $blacklistResource;
    }

    public function execute()
    {

        $parameters = $this->request->getParams();
        $quote = $this->checkoutSession->getQuote();
        $this->eventManager->dispatch(
            'amasty_ilyarog_promo_sku'
        );

        $blacklist = $this->blacklistFactory->create();

        $this->blacklistResource->load(
            $blacklist,
            $parameters['sku'],
            'sku'
        );
        try {
            if (!$quote->getId()) {
                $quote->save();
            }

            $product = $this->productRepository->get($parameters['sku']);
            if ($product->getTypeId() == 'simple') {
                if ($parameters['sku'] === $blacklist->getSku()) {
                    if ($parameters['qty'] > $blacklist->getQty() + $quote->getItemsQty()) {
                        $quote->addProduct($product, ($blacklist->getQty() - $quote->getItemsQty()));
                        $quote->save();
                        $this->messageManager->addErrorMessage("Товар был добавлен в количестве: " . ($blacklist->getQty() - $quote->getItemsQty()) . ' штук.');
                    } else {
                        $quote->addProduct($product, $parameters['qty']);
                        $quote->save();
                        $this->messageManager->addSuccessMessage('Товар добавлен');}
                } else {
                    $blacklist->setSku($parameters['sku']);
                    $this->blacklistResource->save($blacklist);
                }
            } else {
                $this->messageManager->addErrorMessage('Товар не simple');
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage('Такого продукта не существует');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage('Товара недостаточно');
        }
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
