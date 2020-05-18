<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Models\Article\Article;

class Price implements ArticleFieldInterface
{
    public function getName(): string
    {
      return 'Price';
    }

    public function getValue(Article $article): string
    {
        // TODO: Implement getValue() method.
    }
}
