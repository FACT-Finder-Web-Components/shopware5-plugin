<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Template_Manager as TemplateManager;
use OmikronFactfinder\Components\Configuration;

class TemplateRegistration implements SubscriberInterface
{
    /** @var Configuration */
    private $configuration;

    /** @var TemplateManager */
    private $templateManager;

    /** @var string */
    private $pluginDirectory;

    public function __construct(
        Configuration $configuration,
        TemplateManager $templateManager,
        string $pluginDirectory
    ) {
        $this->configuration   = $configuration;
        $this->templateManager = $templateManager;
        $this->pluginDirectory = $pluginDirectory;
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
            $this->templateManager->addTemplateDir($this->pluginDirectory . '/Resources/views');
        }
    }
}
