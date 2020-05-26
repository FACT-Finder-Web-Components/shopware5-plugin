<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Components\Routing\Router;
use Shopware\Models\Article\Article;

class Deeplink implements ArticleFieldInterface
{
    /** @var Router */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getName(): string
    {
        return 'Deeplink';
    }

    public function getValue(Article $article): string
    {
        return $this->router->assemble([
            'module'     => 'frontend',
            'controller' => 'detail',
            'sArticle'   => $article->getMainDetail()->getArticleId(),
        ]);
    }
}
