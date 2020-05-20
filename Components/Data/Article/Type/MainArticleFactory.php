<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Filter\TextFilter;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Article;
use IteratorAggregate;

class MainArticleFactory
{
    /** @var VariantFactory */
    private $variantFactory;

    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var TextFilter */
    private $filter;

    /** @var array */
    private $articleFields;

    public function __construct(
        VariantFactory $variantFactory,
        NumberFormatter $numberFormatter,
        TextFilter $textFilter,
        IteratorAggregate $articleFields
    ) {
        $this->variantFactory  = $variantFactory;
        $this->numberFormatter = $numberFormatter;
        $this->filter          = $textFilter;
        $this->articleFields   = $articleFields;
    }

    public function create(Article $article)
    {
        return new MainArticle($article, $this->numberFormatter, $this->variantFactory, $this->filter, $this->articleFields);
    }
}
