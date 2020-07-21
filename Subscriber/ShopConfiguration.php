<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs as EventArgs;
use Psr\Container\ContainerInterface;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Models\Shop\Shop;

class ShopConfiguration implements SubscriberInterface
{
    /** @var ConfigReader */
    private $configReader;

    /** @var string */
    private $pluginName;

    public function __construct(ConfigReader $configReader, string $pluginName)
    {
        $this->configReader = $configReader;
        $this->pluginName   = $pluginName;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Bootstrap_AfterRegisterResource_shop' => 'onShopRegistration',
        ];
    }

    public function onShopRegistration(EventArgs $args): void
    {
        $this->reloadConfiguration($args['subject'], $args['resource']);
    }

    private function reloadConfiguration(ContainerInterface $container, Shop $shop): void
    {
        $config = $this->configReader->getByPluginName($this->pluginName, $shop);
        $container->set('omikron_factfinder.plugin_config', $config);
    }
}
