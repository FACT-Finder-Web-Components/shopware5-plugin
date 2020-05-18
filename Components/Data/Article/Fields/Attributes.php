<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Models\Article\Article;

class Attributes implements ArticleFieldInterface
{
    public function getName(): string
    {
       return 'Attributes';
    }

    public function getValue(Article $article): string
    {
       $properties = $article->getPropertyGroup();
       //@todo implement;
    }
}
