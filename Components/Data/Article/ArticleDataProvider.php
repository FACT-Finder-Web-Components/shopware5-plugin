<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Type\MainArticleFactory;
use OmikronFactfinder\Components\Data\DataProviderInterface;

class ArticleDataProvider implements DataProviderInterface
{
    /** @var Articles[] */
    private $articles;

    /** @var MainArticleFactory */
    private $mainArticleFactory;

    public function __construct(Articles $articles, MainArticleFactory $mainArticleFactory)
    {
        $this->articles           = $articles;
        $this->mainArticleFactory = $mainArticleFactory;
    }

    public function getEntities(): iterable
    {
        yield from []; // init generator: Prevent errors in case of an empty product collection
        foreach ($this->articles as $article) {
            yield from $this->mainArticleFactory->create($article)->getEntities();
        }
    }
}
