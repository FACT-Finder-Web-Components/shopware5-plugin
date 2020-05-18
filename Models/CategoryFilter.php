<?php

declare(strict_types=1);

namespace OmikronFactfinder\Models;

use Shopware\Models\Category\Repository as CategoryRepository;

class CategoryFilter
{
    /** @var CategoryRepository */
    private $repository;

    /** @var string */
    private $fieldName;

    public function __construct(CategoryRepository $categoryRepository, string $fieldName)
    {
        $this->repository = $categoryRepository;
        $this->fieldName  = $fieldName;
    }

    public function getValue(int $categoryId): array
    {
        return (array) sprintf('filter=%s:%s', $this->fieldName, urlencode(implode('/', $this->getPath($categoryId))));
    }

    private function getPath(int $id): array
    {
        return array_slice($this->repository->getPathById($id, 'name'), 1);
    }
}
