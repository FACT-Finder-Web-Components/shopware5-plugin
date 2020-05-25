<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Models\Category\Repository as CategoryRepository;

class CategoryFilterTest extends TestCase
{
    /** @var MockObject|CategoryRepository */
    private $repository;

    protected function setUp()
    {
        $this->repository = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathById'])
            ->getMock();
    }

    public function test_calculates_the_category_filter()
    {
        $this->repository->method('getPathById')->willReturn(['ROOT', 'Ausrüstung', 'Bücher & Karten']);
        $path = implode('/', array_map('rawurlencode', ['Ausrüstung', 'Bücher & Karten']));

        $categoryPath = new CategoryFilter($this->repository, 'Category');
        $this->assertSame($categoryPath->getValue(42), ['filter=' . rawurlencode('Category:' . $path)]);
    }
}
