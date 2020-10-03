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

    public function getValue(int $categoryId): array
    {
        $path = implode('/', array_map('rawurlencode', $this->getPath($categoryId)));
        return ['filter=' . rawurlencode(sprintf('%s:%s', $this->fieldName, $path))];
    }

    private function getPath(int $id): array
    {
        return array_slice($this->repository->getPathById($id, 'name'), 1);
    }
}
