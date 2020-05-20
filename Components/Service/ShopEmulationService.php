<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use Shopware\Components\Routing\Context;
use Shopware\Components\Routing\Router;
use Shopware\Components\ShopRegistrationServiceInterface;
use Shopware\Models\Shop\Repository as ShopRepository;
use Shopware_Components_Config as Config;

class ShopEmulationService
{
    /** @var ShopRegistrationServiceInterface */
    private $shopRegistration;

    /** @var Config */
    private $config;

    /** @var ShopRepository */
    private $repository;

    /** @var Router */
    private $router;

    public function __construct(
        ShopRepository $repository,
        ShopRegistrationServiceInterface $shopRegistration,
        Router $router,
        Config $config
    ) {
        $this->repository       = $repository;
        $this->shopRegistration = $shopRegistration;
        $this->config           = $config;
        $this->router           = $router;
    }

    public function emulateShop(int $shopId, callable $proceed)
    {
        $context = $this->router->getContext();

        try {
            $shop = $this->repository->getActiveById($shopId);
            $this->router->setContext(Context::createFromShop($shop, $this->config));
            $this->shopRegistration->registerShop($shop);

            $proceed();
        } finally {
            $this->router->setContext($context);
        }
    }
}
