<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_ActionEventArgs as EventArgs;
use Shopware\Components\Plugin\ConfigReader;

class Frontend implements SubscriberInterface
{
    /** @var string */
    private $pluginName;

    /** @var string */
    private $pluginDirectory;

    /** @var ConfigReader */
    private $configReader;

    public function __construct(string $pluginName, string $pluginDirectory, ConfigReader $configReader)
    {
        $this->pluginName      = $pluginName;
        $this->pluginDirectory = $pluginDirectory;
        $this->configReader    = $configReader;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch',
        ];
    }

    public function onPostDispatch(EventArgs $args): void
    {
        if ($this->isEnabled()) {
            $view = $args->getSubject()->View();
            $view->addTemplateDir($this->pluginDirectory . '/Resources/views');
        }
    }

    private function isEnabled(): bool
    {
        return (bool) $this->configReader->getByPluginName($this->pluginName)['ffEnabled'];
    }
}
