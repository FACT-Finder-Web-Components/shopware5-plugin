<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Service\ShopEmulationService;
use Shopware\Components\Routing\Router;
use Shopware\Models\Article\Article;

class Url implements ArticleFieldInterface
{
    /** @var Router */
    private $router;

    /** @var ShopEmulationService  */
    private $shopEmulation;

    public function __construct(Router $router, ShopEmulationService $shopEmulation)
    {
        $this->router = $router;
        $this->shopEmulation = $shopEmulation;
    }

    public function getValue(Article $article): string
    {
        $context = $this->shopEmulation->getContext();
        return $this->router->assemble(
            [
                'module' => 'frontend',
                'controller' => 'detail',
                'sArticle' => $article->getMainDetail()->getArticleId(),
            ], $context
        );
    }
}
