<?php

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Models\Article\Article;

interface ArticleFieldInterface
{
    public function getName(): string;

    public function getValue(Article $article): string;
}
