<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use Shopware\Components\Api\Resource\Article as ArticleResource;
use Shopware\Components\Api\Resource\Resource;

class Articles implements \IteratorAggregate
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
        $this->articleResource->setResultMode(Resource::HYDRATE_OBJECT);
        for ($page = 0; $list = $this->getArticles($page, $this->batchSize); $page++) {
            yield from $list;
        }
    }

    private function getArticles(int $page, int $pageSize): iterable
    {
        return $this->articleResource->getList($page * $pageSize, $pageSize)['data'];
    }
}
