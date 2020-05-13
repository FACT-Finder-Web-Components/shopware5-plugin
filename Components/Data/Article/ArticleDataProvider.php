<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\DataProviderInterface;
use Shopware\Models\Article\Article;

class ArticleDataProvider implements DataProviderInterface
{
    private $articles;

    public function __construct(Articles $articles)
    {
        $this->articles = $articles;
    }

    public function getEntities(): iterable
    {
        yield from []; // init generator: Prevent errors in case of an empty product collection
        /** @var Article $article */
        foreach ($this->articles as $article) {
            yield from $this->entitiesFrom($article)->getEntities();
            yield from $article;
        }
    }

    private function entitiesFrom() {
//        return
    }
}
