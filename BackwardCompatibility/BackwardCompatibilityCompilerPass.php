<?php

declare(strict_types=1);

namespace OmikronFactfinder\BackwardCompatibility;

use OmikronFactfinder\Components\Data\Article\Articles;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class BackwardCompatibilityCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has('shopware.components.shop_registration_service')) {
            return; // On Shopware 5.6, proceed...
        }

        $shopRegistration = new Definition(ShopRegistrationService::class, [new Reference('service_container')]);
        $container->setDefinition('shopware.components.shop_registration_service', $shopRegistration);

        if ($container->has(Articles::class)) {
            $articleResource = new Definition(ArticleResource::class, [new Reference('translation')]);
            $articleResource->addMethodCall('setManager', [new Reference('models')]);
            $container->getDefinition(Articles::class)->setArgument('$articleResource', $articleResource);
        }
    }
}
