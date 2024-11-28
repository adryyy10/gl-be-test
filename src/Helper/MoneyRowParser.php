<?php

namespace App\Helper;

class MoneyRowParser
{
    public function parse(string $value): float
    {
        return !empty($value) ? (float) str_replace(['£', ','], '', $value) : 0.0;
    }
}
