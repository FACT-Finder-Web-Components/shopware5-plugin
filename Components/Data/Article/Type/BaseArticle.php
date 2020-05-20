<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\ExportEntityInterface;
use OmikronFactfinder\Components\Filter\TextFilter;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Article;

abstract class BaseArticle implements ExportEntityInterface
{
    protected $article;

    /** @var NumberFormatter */
    protected $numberFormatter;

    /** @var TextFilter */
    protected $filter;

    public function __construct(Article $article, NumberFormatter $numberFormatter, TextFilter $textFilter)
    {
        $this->article         = $article;
        $this->numberFormatter = $numberFormatter;
        $this->filter          = $textFilter;
    }

    public function getId(): int
    {
        return (int)$this->article->getId();
    }

    public function toArray(): array
    {
        return [
            'ProductNumber' => (string)$this->article->getMainDetail()->getNumber(),
            'Master' => (string)$this->article->getMainDetail()->getNumber(),
            'Name' => (string)$this->article->getName(),
            'EAN' => (string)$this->article->getMainDetail()->getEan(),
            'Weight' => (float)$this->article->getMainDetail()->getWeight(),
            'Description' => (string)$this->article->getDescriptionLong(),
            'Short' => (string)$this->article->getDescription(),
            'Price' => $this->numberFormatter->format((float)$this->article->getMainDetail()->getPrices()[0]->getPrice()),
            'Brand' => (string)$this->article->getSupplier()->getName(),
            'Availability' => (int)$this->article->getMainDetail()->getActive(),
            'HasVariants' => $this->article->getDetails()->count() ? 1 : 0,
            'ShopwareId' => (string)$this->article->getId(),
        ];
    }
}
