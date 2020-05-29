<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\ExportEntityInterface;
use OmikronFactfinder\Components\Filter\ExtendedTextFilter;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

abstract class BaseArticle implements ExportEntityInterface
{
    /** @var Article */
    protected $article;

    /** @var Detail */
    protected $detail;

    /** @var ExtendedTextFilter */
    protected $filter;

    public function setDetail(Detail $detail)
    {
        $this->detail = $detail;
    }

    public function setArticle(Article $article)
    {
        $this->article = $article;
    }

    public function setTextFilter(ExtendedTextFilter $textFilter)
    {
        $this->filter = $textFilter;
    }

    public function getId(): int
    {
        return (int) $this->detail->getId();
    }

    public function toArray(): array
    {
        return [
            'ProductNumber' => (string) $this->article->getMainDetail()->getNumber(),
            'Master'        => (string) $this->article->getMainDetail()->getNumber(),
            'Name'          => (string) $this->article->getName(),
            'EAN'           => (string) $this->article->getMainDetail()->getEan(),
            'Weight'        => (float) $this->article->getMainDetail()->getWeight(),
            'Description'   => (string) $this->article->getDescriptionLong(),
            'Short'         => (string) $this->article->getDescription(),
            'Brand'         => (string) $this->article->getSupplier()->getName(),
            'Availability'  => (int) $this->article->getMainDetail()->getActive(),
        ];
    }
}
