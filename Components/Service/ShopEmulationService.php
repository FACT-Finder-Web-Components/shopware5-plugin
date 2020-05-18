<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use Shopware\Components\Model\ModelManager;
use Shopware\Components\ShopRegistrationServiceInterface;
use Shopware\Models\Shop\Repository;
use Shopware\Models\Shop\Shop;
use Shopware\Components\Routing\Context;
use Shopware_Components_Config as Config;

class ShopEmulationService
{
    /** @var ModelManager */
    private $modelManager;

    /** @var ShopRegistrationServiceInterface */
    private $registrationService;

    /** @var Config */
    private $config;

    private $shop;

    private $context;

    public function __construct(ModelManager $modelManager, ShopRegistrationServiceInterface $registrationService, Config $config)
    {
        $this->modelManager = $modelManager;
        $this->registrationService = $registrationService;
        $this->config = $config;
    }

    public function emulateShop(int $shopId)
    {
        /** @var Repository $repository */
        $repository = $this->modelManager->getRepository(Shop::class);
        $this->shop = $repository->getActiveById($shopId);
        $this->registrationService->registerShop($this->shop);
        $this->context = Context::createFromShop($this->shop, $this->config);
    }

    public function getShop()
    {
        return $this->shop;
    }

    public function getContext()
    {
        return $this->context;
    }
}
