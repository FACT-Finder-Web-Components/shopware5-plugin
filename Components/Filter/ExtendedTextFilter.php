<?php

namespace OmikronFactfinder\Components\Filter;

class ExtendedTextFilter extends TextFilter
{
    private $forbiddenChars = '/[|#=]/';

    public function filterValue(string $value): string
    {
        return trim(preg_replace($this->forbiddenChars, ' ', parent::filterValue($value)));
    }
}
