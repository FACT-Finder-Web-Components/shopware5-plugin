<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Formatter;

class NumberFormatter
{
    public function format(float $number, int $precision = 2): string
    {
        return sprintf("%.{$precision}F", round($number, $precision));
    }
}
