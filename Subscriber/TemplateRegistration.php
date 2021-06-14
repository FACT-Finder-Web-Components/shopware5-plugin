<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Template_Manager as TemplateManager;
use OmikronFactfinder\Components\Configuration;
use OmikronFactfinder\Components\Data\Article\PriceCurrencyFields;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;

class TemplateRegistration implements SubscriberInterface
{
    /** @var Configuration */
    private $configuration;

    /** @var TemplateManager */
    private $templateManager;

    /** @var PriceCurrencyFields */
    private $priceCurrencyFields;

    /** @var ContextServiceInterface */
    private $contextService;

    /** @var string */
    private $pluginDirectory;

    /** @var array */
    private $fieldRoles;

    public function __construct(
        Configuration $configuration,
        TemplateManager $templateManager,
        PriceCurrencyFields $priceCurrencyFields,
        ContextServiceInterface $contextService,
        string $pluginDirectory,
        array $fieldRoles
    ) {
        $this->configuration       = $configuration;
        $this->templateManager     = $templateManager;
        $this->priceCurrencyFields = $priceCurrencyFields;
        $this->contextService      = $contextService;
        $this->pluginDirectory     = $pluginDirectory;
        $this->fieldRoles          = $fieldRoles;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch',
        ];
    }

    public function onPostDispatch(): void
    {
        if ($this->configuration->isEnabled()) {
            $currencyField = sprintf('%s_%s', $this->fieldRoles['price'], $this->contextService->getShopContext()->getCurrency()->getName());
            $this->templateManager->addTemplateDir($this->pluginDirectory . '/Resources/views');
            $this->templateManager->assign('ffFieldRoles', $this->fieldRoles);
            $this->templateManager->assign('currencyFields', implode(',', array_keys($this->priceCurrencyFields->getPriceCurrencyFields())));
            $this->templateManager->assign('activeCurrency', "{{record.$currencyField}}");
            $this->templateManager->assign('activeCurrencyField', $currencyField);
        }
    }
}
