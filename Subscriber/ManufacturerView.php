<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_ActionEventArgs as EventArgs;
use OmikronFactfinder\Components\Configuration;

class ManufacturerView implements SubscriberInterface
{
    /** @var Configuration */
    private $configuration;

    /** @var array */
    private $fieldRoles;

    public function __construct(Configuration $configuration, array $fieldRoles)
    {
        $this->configuration = $configuration;
        $this->fieldRoles    = $fieldRoles;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'onPostDispatch',
        ];
    }

    public function onPostDispatch(EventArgs $args): void
    {
        if ($this->configuration->useForCategories()) {
            $view         = $args->getSubject()->View();
            $manufacturer = $view->getAssign('manufacturer');

            if ($manufacturer) {
                $view->extendsTemplate('frontend/factfinder/manufacturer.tpl');
                $view->assign('ffManufacturerFilter', 'filter=' . rawurlencode(sprintf('%s:%s', $this->fieldRoles['brand'], $manufacturer->getName())));
                return;
            }
        }
    }
}
