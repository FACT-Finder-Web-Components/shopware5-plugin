<?php

declare(strict_types=1);

namespace OmikronFactfinder;

use OmikronFactfinder\BackwardCompatibility\BackwardCompatibilityCompilerPass;
use OmikronFactfinder\Components\Data\Article\Fields\ArticleFieldInterface;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OmikronFactfinder extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new BackwardCompatibilityCompilerPass());
        $container->registerForAutoconfiguration(ArticleFieldInterface::class)->addTag('factfinder.export.field');
    }
}
