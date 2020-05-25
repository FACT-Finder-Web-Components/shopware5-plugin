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
        $prices  = $article->getMainDetail()->getPrices();
        if (!$prices->count()) {
            return '0';
        }

        $taxRate = $article->getTax()->getTax();
        return (string)$this->numberFormatter->format($prices[0]->getPrice() * (($taxRate + 100) / 100));
    }
}
