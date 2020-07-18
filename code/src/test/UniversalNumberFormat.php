<?php

declare(strict_types=1);

namespace App\test;

use App\test\DataProvider\UniversalNumberProviderInterface;

class UniversalNumberFormat implements NumberFormatterInterface
{
    /**
     * @var UniversalNumberProviderInterface[]
     */
    private iterable $providers;
    private ?UniversalNumberProviderInterface $provider = null;

    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function format(int $number): string
    {
        if (!$this->provider) {
            throw new \InvalidArgumentException(sprintf('no providers for number %s', $number));
        }

        return $this->provider->format($number);
    }

    public function supports(int $number): bool
    {
        $this->provider = null;

        return $this->getInternalSupports($number);
    }

    public function getInternalSupports(int $number): bool
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($number)) {
                $this->provider = $provider;

                return true;
            }
        }

        return false;
    }

}