<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Article;
use IteratorAggregate;

class MainArticleFactory
{
    /** @var VariantFactory */
    private $variantFactory;

    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var array */
    private $articleFields;

    public function __construct(VariantFactory $variantFactory, NumberFormatter $numberFormatter, IteratorAggregate $articleFields)
    {
        $this->variantFactory = $variantFactory;
        $this->numberFormatter = $numberFormatter;
        $this->articleFields = $articleFields;
    }

    public function create(Article $article)
    {
        return new MainArticle($article, $this->variantFactory, $this->numberFormatter, $this->articleFields);
    }
}
