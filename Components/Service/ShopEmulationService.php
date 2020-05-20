<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use Shopware\Components\Model\ModelManager;
use Shopware\Components\Routing\Context;
use Shopware\Components\ShopRegistrationServiceInterface;
use Shopware\Models\Shop\Repository;
use Shopware\Models\Shop\Shop;
use Shopware_Components_Config as Config;

class ShopEmulationService
{
    /** @var ModelManager */
    private $modelManager;

    /** @var ShopRegistrationServiceInterface */
    private $registrationService;

    /** @var Config */
    private $config;

    /** @var Shop|null */
    private $shop;

    /** @var context|null */
    private $context;

    /** @var bool */
    private $startedEmulation = false;

    public function __construct(ModelManager $modelManager, ShopRegistrationServiceInterface $registrationService, Config $config)
    {
        $this->modelManager        = $modelManager;
        $this->registrationService = $registrationService;
        $this->config              = $config;
    }

    public function emulateShop(int $shopId)
    {
        /** @var Repository $repository */
        $repository = $this->modelManager->getRepository(Shop::class);
        $this->shop = $repository->getActiveById($shopId);
        $this->registrationService->registerShop($this->shop);
        $this->context          = Context::createFromShop($this->shop, $this->config);
        $this->startedEmulation = true;
    }

    public function getShop(): Shop
    {
        if (!$this->startedEmulation) {
            $this->noShopEmulatedException();
        }
        return $this->shop;
    }

    public function getContext(): Context
    {
        if (!$this->startedEmulation) {
            $this->noShopEmulatedException();
        }
        return $this->context;
    }

    private function noShopEmulatedException()
    {
        throw new \BadMethodCallException('No shop emulated. Please use `emulateShop` before');
    }
}
