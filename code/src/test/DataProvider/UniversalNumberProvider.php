<?php

declare(strict_types=1);

namespace App\test\DataProvider;

class UniversalNumberProvider implements UniversalNumberProviderInterface
{
    private \Closure $supports;
    private \Closure $format;

    public function __construct(\Closure $format, \Closure $supports)
    {
        $this->supports = $supports;
        $this->format = $format;
    }

    public function supports(int $number): bool
    {
        $func = $this->supports;

        return $func($number);
    }

    public function format(int $number): string
    {
        $func = $this->format;

        return $func($number);
    }
}