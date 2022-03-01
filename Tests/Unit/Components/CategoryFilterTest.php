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
        $this->repository->method('getPathById')->willReturn(['ROOT', 'Ausrüstung100%', 'Bücher / Karten']);

        $categoryPath = new CategoryFilter($this->repository, 'Category');
        $this->assertSame($categoryPath->getValue(42), ['filter=Category:Ausr%C3%BCstung100%2525%2FB%C3%BCcher+%252F+Karten']);
    }
}
