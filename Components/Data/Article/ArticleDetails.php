<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use Shopware\Components\Api\Resource\Article as ArticleResource;
use Shopware\Components\Api\Resource\Resource;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

class ArticleDetails implements \IteratorAggregate
{
    /** @var ArticleResource */
    private $articleResource;

    /** @var int */
    private $batchSize;

    public function __construct(ArticleResource $articleResource, int $batchSize = 100)
    {
        $this->articleResource = $articleResource;
        $this->batchSize       = $batchSize;
    }

    public function getIterator()
    {
        yield from [];
        for ($page = 0; $list = $this->getArticles($page, $this->batchSize); $page++) {
            foreach ($list as $article) {
                /* @var Article $article */
                yield from $article->getDetails()->filter(function (Detail $detail): bool {
                    return (bool) $detail->getActive();
                });
            }
        }
    }

    private function getArticles(int $page, int $pageSize): iterable
    {
        $this->articleResource->setResultMode(Resource::HYDRATE_OBJECT);
        return $this->articleResource->getList($page * $pageSize, $pageSize, ['active' => true])['data'];
    }
}
