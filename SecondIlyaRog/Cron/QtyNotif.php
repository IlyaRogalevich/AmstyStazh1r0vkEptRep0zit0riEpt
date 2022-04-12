<?php

namespace Amasty\SecondIlyaRog\Cron;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Amasty\IlyaRog\Model\BlacklistRepository;
use Amasty\IlyaRog\Model\ResourceModel\Blacklist;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Mail\Template\Factory;

class QtyNotif implements ActionInterface
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
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    /**
     * @var Blacklist
     */
    private $blacklistResource;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var Factory
     */
    private $templateFactory;

    public function __construct(
        ResultFactory $resultFactory,
        ScopeConfigInterface $scopeConfig,
        BlacklistRepository $blacklistRepository,
        Blacklist $blacklistResource,
        TransportBuilder $transportBuilder,
        Factory $templateFactory
    ) {
        $this->resultFactory = $resultFactory;
        $this->scopeConfig = $scopeConfig;
        $this->blacklistRepository = $blacklistRepository;
        $this->transportBuilder = $transportBuilder;
        $this->templateFactory = $templateFactory;
        $this->blacklistResource = $blacklistResource;
    }

    public function execute()
    {
        $blacklist = $this->blacklistRepository->getById(4);

        $templateId = 'for_sku_config_general_email_templateid';
        $templateVars = [
            'blacklist' => $blacklist,
            'id' => $blacklist->getId(),
            'sku' => $blacklist->getData('sku'),
            'qty' => $blacklist->getData('qty'),
            'email_body' => $blacklist->getData('email_body')
        ];
        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => 0
        ];

        $email = $this->scopeConfig->getValue('test_config/general/enabled');

        $transport= $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom([
                'name' => 'Test',
                'email' => 'admin@example.com'
            ])
            ->addTo($email, 'Ilya')
            ->getTransport();

        $transport->sendMessage();
        $blacklist->setEmail_Body();
        $this->blacklistResource->save($transport);
    }
}