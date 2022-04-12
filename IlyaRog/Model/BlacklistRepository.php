<?php

namespace Amasty\IlyaRog\Model;

use Amasty\IlyaRog\Model\BlacklistFactory;
use Amasty\IlyaRog\Model\ResourceModel\Blacklist;

class BlacklistRepository
{
    /**
     * @var BlacklistFactory
     */
    private $blacklistFactory;

    /**
     * @var ResourceModel\Blacklist
     */
    private $blacklistResource;

    public function __construct(
        BlacklistFactory $blacklistFactory,
        ResourceModel\Blacklist $blacklistResource
    ) {
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistResource = $blacklistResource;
    }

    public function getById(int $id): \Amasty\IlyaRog\Model\Blacklist
    {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load(
            $blacklist,
            $id,
            'id'
        );

        return $blacklist;
    }

    public function deleteById(int $id)
    {
        $blacklist = $this->getById($id);
        $this->blacklistResource->delete($blacklist);
    }
}
