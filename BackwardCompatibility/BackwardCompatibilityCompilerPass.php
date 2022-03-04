<?php

declare(strict_types=1);

namespace OmikronFactfinder\BackwardCompatibility;

use OmikronFactfinder\Components\Data\Article\ArticleDetails;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @deprecated 5.5 backward compatibility will be removed in next major version
 */
class BackwardCompatibilityCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has('shopware.components.shop_registration_service')) {
            return; // On Shopware 5.6, proceed...
        }

        $shopRegistration = new Definition(ShopRegistrationService::class, [new Reference('service_container')]);
        $container->setDefinition('shopware.components.shop_registration_service', $shopRegistration);

        if ($container->has(ArticleDetails::class)) {
            $articleResource = new Definition(ArticleResource::class, [new Reference('translation')]);
            $articleResource->addMethodCall('setManager', [new Reference('models')]);
            $container->getDefinition(ArticleDetails::class)->setArgument('$articleResource', $articleResource);
        }
    }
}
