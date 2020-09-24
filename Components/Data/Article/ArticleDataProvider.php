<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Type\ProviderFactory;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use Shopware\Models\Article\Article;

class ArticleDataProvider implements DataProviderInterface
{
    /** @var Articles */
    private $articles;

    /** @var ProviderFactory */
    private $providerFactory;

    public function __construct(Articles $articles, ProviderFactory $providerFactory)
    {
        $this->articles        = $articles;
        $this->providerFactory = $providerFactory;
    }

    public function getEntities(): iterable
    {
        yield from []; // init generator: Prevent errors in case of an empty product collection
        /** @var Article $article */
        foreach ($this->articles as $article) {
            yield from $this->providerFactory->create($article->getMainDetail())->getEntities();
        }
    }
}
