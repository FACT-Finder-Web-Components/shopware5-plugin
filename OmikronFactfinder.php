<?php

declare(strict_types=1);

namespace OmikronFactfinder;

use OmikronFactfinder\Components\Data\Article\Fields\ArticleFieldInterface;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OmikronFactfinder extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->registerForAutoconfiguration(ArticleFieldInterface::class)->addTag('factfinder.export.field');
    }
}
