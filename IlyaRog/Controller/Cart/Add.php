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

class Add implements ActionInterface
{
    /**
     * @var EventManager
     */
    private $eventManager;

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

    public function __construct(
        ResultFactory                             $resultFactory,
        ScopeConfigInterface                      $scopeConfig,
        Session                                   $checkoutSession,
        ProductRepositoryInterface                $productRepository,
        RequestInterface                          $request,
        EventManager                              $eventManager,
        ManagerInterface                          $messageManager
    )
    {
        $this->resultFactory = $resultFactory;
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->eventManager = $eventManager;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {

        $parameters = $this->request->getParams();
        $quote = $this->checkoutSession->getQuote();
        $this->eventManager->dispatch(
            'amasty_ilyarog_promo_sku'
        );

        try {
            if (!$quote->getId()) {
                $quote->save();
            }

            $product = $this->productRepository->get($parameters['sku']);
            if ($product->getTypeId() == 'simple'){
                $quote->addProduct($product, $parameters['qty']);
                $quote->save();
                $this->messageManager->addSuccessMessage('Товар добавлен');
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
