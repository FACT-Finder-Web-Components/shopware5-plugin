<?php

namespace OmikronFactfinder\Components\Filter;

interface FilterInterface
{
    public function filterValue(string $value): string;
}
