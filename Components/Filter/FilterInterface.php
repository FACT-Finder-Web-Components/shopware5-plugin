<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Filter;

interface FilterInterface
{
    public function filterValue(string $value): string;
}
