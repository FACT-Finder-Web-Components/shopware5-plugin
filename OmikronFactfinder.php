<?php

declare(strict_types=1);

namespace OmikronFactfinder;

use OmikronFactfinder\BackwardCompatibility\BackwardCompatibilityCompilerPass;
use OmikronFactfinder\Components\Data\Article\Fields\FieldInterface;
use OmikronFactfinder\Components\Data\Article\Fields\PriceCurrency;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OmikronFactfinder extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new BackwardCompatibilityCompilerPass());
        $container->registerForAutoconfiguration(FieldInterface::class)->addTag('factfinder.export.field');
    }
}
