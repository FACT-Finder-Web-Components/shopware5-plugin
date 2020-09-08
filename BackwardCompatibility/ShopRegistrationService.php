<?php

declare(strict_types=1);

namespace Shopware\Components;

interface ShopRegistrationServiceInterface
{
}

namespace OmikronFactfinder\BackwardCompatibility;

use Shopware\Components\ShopRegistrationServiceInterface;
use Shopware\Components\Theme\Inheritance;
use Shopware\Models\Shop\Shop;
use Shopware\Models\Shop\Template;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ShopRegistrationService implements ShopRegistrationServiceInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function registerShop(Shop $shop): void
    {
        $this->registerResources($shop);
        $this->resetTemplate($shop);
    }

    public function registerResources(Shop $shop): void
    {
        $this->container->set('Shop', $shop);

        /** @var \Zend_Locale $locale */
        $locale = $this->container->get('locale');
        $locale->setLocale($shop->getLocale()->toString());

        /** @var \Zend_Currency $currency */
        $currency = $this->container->get('currency');
        $currency->setLocale($locale);
        $currency->setFormat($shop->getCurrency()->toArray());

        /** @var \Shopware_Components_Config $config */
        $config = $this->container->get('config');
        $config->setShop($shop);

        /** @var \Shopware_Components_Snippet_Manager $snippets */
        $snippets = $this->container->get('snippets');
        $snippets->setShop($shop);

        /** @var \Enlight_Plugin_PluginManager $plugins */
        $plugins = $this->container->get('plugins');

        /** @var \Shopware_Components_Plugin_Namespace $pluginNamespace */
        foreach ($plugins as $pluginNamespace) {
            if ($pluginNamespace instanceof \Shopware_Components_Plugin_Namespace) {
                $pluginNamespace->setShop($shop);
            }
        }

        // Initializes the frontend session to prevent output before session started.
        $this->container->get('session');

        /** @var \Shopware_Components_TemplateMail $templateMail */
        $templateMail = $this->container->get('templatemail');
        $templateMail->setShop($shop);

        // Reset mail transport to have right configuration
        if ($this->container->initialized('mailtransport')) {
            $this->container->reset('mailtransport');
            $this->container->load('mailtransport');
        }
    }

    public function resetTemplate(Shop $shop): void
    {
        if ($shop->getTemplate() === null) {
            return;
        }

        /** @var \Enlight_Template_Manager $templateManager */
        $templateManager = $this->container->get('template');
        $template        = $shop->getTemplate();
        $localeName      = $shop->getLocale()->toString();

        if ($template->getVersion() === 3) {
            $this->registerTheme($template);
        } else {
            throw new \Exception(sprintf(
                'Tried to load unsupported template version %s for template: %s',
                $template->getVersion(),
                $template->getName()
            ));
        }

        $templateManager->setCompileId(
            'frontend' .
            '_' . $template->toString() .
            '_' . $localeName .
            '_' . $shop->getId()
        );
    }

    private function registerTheme(Template $template): void
    {
        /** @var \Enlight_Template_Manager $templateManager */
        $templateManager = $this->container->get('template');

        /** @var Inheritance $inheritance */
        $inheritance = $this->container->get('theme_inheritance');

        $path = $inheritance->getTemplateDirectories($template);
        $templateManager->setTemplateDir($path);
    }
}
