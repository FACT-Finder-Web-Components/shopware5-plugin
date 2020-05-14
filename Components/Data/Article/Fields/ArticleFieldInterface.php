<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Models\Article\Article;

interface ArticleFieldInterface
{
    public function getValue(Article $article): string;
}
