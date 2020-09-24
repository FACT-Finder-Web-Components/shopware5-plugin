<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Components\Routing\Router;
use Shopware\Models\Article\Detail;

class Deeplink implements FieldInterface
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

    public function getValue(Detail $detail): string
    {
        return $this->router->assemble([
            'module'     => 'frontend',
            'controller' => 'detail',
            'sArticle'   => $detail->getArticle()->getMainDetail()->getArticleId(),
        ]);
    }
}
