<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Filter;

class ExtendedTextFilter extends TextFilter
{
    private $forbiddenChars = '/[|#=]/';

    public function filterValue(string $value): string
    {
        return trim(preg_replace($this->forbiddenChars, ' ', parent::filterValue($value)));
    }
}
