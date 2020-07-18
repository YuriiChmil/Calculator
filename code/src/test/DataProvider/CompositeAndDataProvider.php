<?php

declare(strict_types=1);

namespace App\test\DataProvider;

class CompositeAndDataProvider implements UniversalNumberProviderInterface
{
    private iterable $providers;

    /**
     * @var UniversalNumberProviderInterface[] $providers
     */
    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function supports(int $number): bool
    {
        foreach ($this->providers as $provider) {
            if (!$provider->supports($number)) {
                return false;
            }
        }

        return true;
    }

    public function format(int $number): string
    {
        $result = '';
        foreach ($this->providers as $provider) {
            $result .= $provider->format($number);
        }

        return $result;
    }

}