<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Models\Article\Article;
use Shopware\Models\Category\Category;

class CategoryPath implements ArticleFieldInterface
{

    public function getName(): string
    {
        return 'CategoryPath';
    }

    public function getValue(Article $article): string
    {
        return array_reduce($article->getAllCategories(), function ($acc, Category $curr) {
            return $acc . '|' . $curr->getName();
        }, '');
    }
}
