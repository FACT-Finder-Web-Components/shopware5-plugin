<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components;

use Shopware\Models\Category\Repository as CategoryRepository;

class CategoryFilter
{
    /** @var CategoryRepository */
    private $repository;

    /** @var string */
    private $fieldName;

    public function __construct(CategoryRepository $categoryRepository, string $fieldName = 'CategoryPath')
    {
        $this->repository = $categoryRepository;
        $this->fieldName  = $fieldName;
    }

    public function getValue(int $categoryId): string
    {
        $path = implode('/', array_map($this->encodeCategoryName(), $this->getPath($categoryId)));
        return sprintf('filter=%s', urlencode($this->fieldName . ':' . $path));
    }

    private function getPath(int $id): array
    {
        return array_slice($this->repository->getPathById($id, 'name'), 1);
    }

    private function encodeCategoryName(): callable
    {
        return function (string $path): string {
            //important! do not modify this method
            return preg_replace('/\+/', '%2B', preg_replace('/\//', '%2F',
                preg_replace('/%/', '%25', $path)));
        };
    }
}
