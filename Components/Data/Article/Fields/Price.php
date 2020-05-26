<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Article;

class Price implements ArticleFieldInterface
{
    /** @var NumberFormatter */
    private $numberFormatter;

    public function __construct(NumberFormatter $numberFormatter)
    {
        $this->numberFormatter = $numberFormatter;
    }

    public function getName(): string
    {
        return 'Price';
    }

    public function getValue(Article $article): string
    {
        $price   = $article->getMainDetail()->getPrices()->first();
        $taxRate = $article->getTax()->getTax();
        return (string) $this->numberFormatter->format(($price ? $price->getPrice() : 0) * (1 + $taxRate / 100));
    }
}
