<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Models\Article\Article;
use Shopware\Models\Category\Category;

class CategoryPath implements ArticleFieldInterface
{
    /** @var string */
    private $fieldName;

    public function __construct(string $fieldName = 'CategoryPath')
    {
        $this->fieldName = $fieldName;
    }

    public function getName(): string
    {
        return $this->fieldName;
    }

    public function getValue(Article $article): string
    {
        $categoryName = $this->categoryName($article->getAllCategories());
        return implode('|', $article->getCategories()->map(function (Category $category) use ($categoryName) {
            return implode('/', array_map($categoryName, $this->getPath($category)));
        })->toArray());
    }

    private function categoryName(array $allCategories): callable
    {
        $names = array_reduce($allCategories, function (array $result, Category $category) {
            return $result + [$category->getId() => $category->getName()];
        }, []);

        return function (int $categoryId) use ($names): string {
            return rawurlencode($names[$categoryId] ?? '');
        };
    }

    private function getPath(Category $category): array
    {
        return array_slice(array_reverse(explode('|', $category->getId() . $category->getPath())), 2);
    }
}
