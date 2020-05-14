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
        $this->batchSize = $batchSize;
    }

    public function getIterator()
    {
        $this->articleResource->setResultMode(Resource::HYDRATE_OBJECT);
        for ($offset = 0; $list = $this->articleResource->getList($offset, $this->batchSize); $offset += $this->batchSize) {
            foreach ($list['data'] as $product) yield $product;
        }
    }
}
