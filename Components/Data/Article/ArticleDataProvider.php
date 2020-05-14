<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Type\MainArticleFactory;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

class ArticleDataProvider implements DataProviderInterface
{
    private $articles;

    /** @var MainArticleFactory */
    private $mainArticleFactory;

    public function __construct(Articles $articles, MainArticleFactory $mainArticleFactory)
    {
        $this->articles = $articles;
        $this->mainArticleFactory = $mainArticleFactory;
    }

    public function getEntities(): iterable
    {
        yield from []; // init generator: Prevent errors in case of an empty product collection
        /** @var Article $article */
        foreach ($this->articles as $article) {
//            var_dump($article->getMainDetail()->getNumber());
            /** @var Detail $detail */
            foreach ($article->getDetails() as $detail) {

                var_dump($detail->getNumber());
                var_dump($detail->getArticle()->getMainDetail()->getNumber());
                var_dump($detail->getArticle()->getDescriptionLong());
                var_dump($detail->getKind());
            }
            yield from $this->mainArticleFactory->create($article)->getEntities();
        }
    }
}
