<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs as EventArgs;
use Shopware\Components\Plugin\ConfigReader;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        /** @var ContainerInterface $container */
        $container = $args['subject'];
        $config    = $this->configReader->getByPluginName($this->pluginName, $args['resource']);
        $container->set('omikron_factfinder.plugin_config', $config);
    }
}
